<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarritoController extends Controller
{

    public function index()
    {
        $carrito = Carrito::first();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.carrito', compact('carrito', 'logo'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $carrito = Carrito::findOrFail($id);
        $carrito->descripcion = $request->descripcion;
        $carrito->save();

        return $this->success_response('Carrito actualizado exitosamente.');
    }
    public function agregarProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()]);
        }

        // Obtener el producto completo de la base de datos
        $producto = Producto::with(['caracteristicas', 'descuentos', 'imagenes', 'categoria'])->findOrFail($request->producto_id);

        // Inicializar el carrito en la sesión si no existe
        if (!session()->has('carrito')) {
            session()->put('carrito', []);
        }

        $carrito = session()->get('carrito');

        // Convertir precio a formato numérico
        $precio = floatval(str_replace([',', '.'], ['', '.'], $producto->precio));

        // Revisar si el producto ya está en el carrito
        $encontrado = false;
        foreach ($carrito as $key => $item) {
            if ($item['producto_id'] == $request->producto_id) {
                $carrito[$key]['cantidad'] += $request->cantidad;
                $encontrado = true;
                break;
            }
        }

        // Si el producto no está en el carrito, agregarlo
        if (!$encontrado) {
            // Calcular valores iniciales
            $subtotal = $request->cantidad * $precio;
            $descuentoCliente = Auth::guard('cliente')->user()->descuento ?? 0;
            $descuentoClienteValor = $subtotal * ($descuentoCliente / 100);
            $total = $subtotal - $descuentoClienteValor;

            $carrito[] = [
                'producto_id' => $request->producto_id,
                'codigo' => $producto->codigo,
                'titulo' => $producto->titulo,
                'cantidad' => $request->cantidad,
                'precio' => $producto->precio,
                'categoria_id' => $producto->categoria_id,
                'unidad_venta' => $producto->unidad,
                'imagen' => $producto->imagenes->first() ? $producto->imagenes->first()->path : null,
                'descuentos' => $producto->descuentos->toArray(),
                'subtotal' => $subtotal,
                'descuento_cliente' => $descuentoCliente,
                'descuento_cliente_valor' => $descuentoClienteValor,
                'descuento_aplicado' => 0,
                'descuento_aplicado_valor' => 0,
                'descuento_tipo' => 'ninguno',
                'total' => $total
            ];
        }

        session()->put('carrito', $carrito);

        // Recalcular los descuentos después de agregar el producto
        $carrito = $this->calcularDescuentosCarrito($carrito);
        session()->put('carrito', $carrito);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito correctamente',
            'cantidad_items' => count($carrito)
        ]);
    }

    public function zona()
    {
        $info = Carrito::first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();

        // Obtener carrito de la sesión
        $carrito = session()->get('carrito', []);

        // Calcular descuentos
        $carrito = $this->calcularDescuentosCarrito($carrito);

        return view('zonaprivada.carrito', compact('info', 'logos', 'contactos', 'carrito'));
    }

    // Modificación para el CarritoController.php - método calcularDescuentosCarrito()
    private function calcularDescuentosCarrito($carrito)
    {
        if (empty($carrito)) {
            return $carrito;
        }

        // Obtener el descuento del cliente
        $descuentoCliente = Auth::guard('cliente')->user()->descuento ?? 0;
        // Definir la tasa de IVA
        $ivaTasa = 21; // 21%

        // Inicializar todos los elementos del carrito con los valores necesarios
        foreach ($carrito as $itemKey => $item) {
            // Asegurarnos de que todas las claves necesarias existan
            $carrito[$itemKey]['descuento_aplicado'] = 0;
            $carrito[$itemKey]['descuento_aplicado_valor'] = 0;
            $carrito[$itemKey]['descuento_cliente'] = $descuentoCliente;
            $carrito[$itemKey]['subtotal'] = $item['cantidad'] * floatval(str_replace([',', '.'], ['', '.'], $item['precio']));
            $carrito[$itemKey]['descuento_cliente_valor'] = $carrito[$itemKey]['subtotal'] * ($descuentoCliente / 100);

            // Calcular el importe después de aplicar el descuento del cliente
            $importeConDescuentoCliente = $carrito[$itemKey]['subtotal'] * (1 - $descuentoCliente / 100);
            $carrito[$itemKey]['importe_con_descuento_cliente'] = $importeConDescuentoCliente;

            // Inicializar el total sin IVA (se actualizará si hay descuentos adicionales)
            $carrito[$itemKey]['total_sin_iva'] = $importeConDescuentoCliente;

            // Inicializar valores de IVA
            $carrito[$itemKey]['iva_tasa'] = $ivaTasa;
            $carrito[$itemKey]['iva_importe'] = 0; // Se calculará después de todos los descuentos

            $carrito[$itemKey]['descuento_tipo'] = 'ninguno';
        }

        // Agrupar productos por descuento_id para calcular descuentos combinados
        $productosPorDescuento = [];

        // Primero, agrupamos los productos por sus IDs de descuento
        foreach ($carrito as $itemKey => $item) {
            if (!empty($item['descuentos'])) {
                foreach ($item['descuentos'] as $descuento) {
                    $descuentoId = $descuento['id'];

                    if (!isset($productosPorDescuento[$descuentoId])) {
                        $productosPorDescuento[$descuentoId] = [
                            'descuento' => $descuento,
                            'productos' => []
                        ];
                    }

                    // Añadir este producto a este grupo de descuento
                    $productosPorDescuento[$descuentoId]['productos'][] = [
                        'carrito_key' => $itemKey,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $item['precio']
                    ];
                }
            }
        }

        // Ahora procesamos cada grupo de descuento
        foreach ($productosPorDescuento as $descuentoId => $grupo) {
            $descuentoInfo = $grupo['descuento'];
            $totalCantidad = 0;

            // Sumar todas las cantidades de este grupo
            foreach ($grupo['productos'] as $prod) {
                $totalCantidad += $prod['cantidad'];
            }

            // Si la cantidad total alcanza el mínimo para este descuento
            if ($totalCantidad >= $descuentoInfo['cantidad_minima']) {
                $porcentajeDescuento = $descuentoInfo['descuento'];

                // Aplicar este descuento a cada producto en este grupo
                foreach ($grupo['productos'] as $prod) {
                    $carritoKey = $prod['carrito_key'];

                    // Solo aplicar si es mejor que cualquier descuento ya aplicado
                    if ($porcentajeDescuento > $carrito[$carritoKey]['descuento_aplicado']) {
                        $subtotal = $carrito[$carritoKey]['subtotal'];
                        $subtotalConDescuentoCliente = $subtotal * (1 - $descuentoCliente / 100);

                        $carrito[$carritoKey]['descuento_aplicado'] = $porcentajeDescuento;
                        $carrito[$carritoKey]['descuento_aplicado_valor'] = $subtotalConDescuentoCliente * ($porcentajeDescuento / 100);
                        $carrito[$carritoKey]['total_sin_iva'] = $subtotalConDescuentoCliente * (1 - $porcentajeDescuento / 100);
                        $carrito[$carritoKey]['descuento_tipo'] = 'combinado';
                        $carrito[$carritoKey]['descuento_id'] = $descuentoId;
                    }
                }
            }
        }

        // También verificar descuentos individuales
        foreach ($carrito as $key => $item) {
            if (!empty($item['descuentos'])) {
                $descuentosAplicables = array_filter($item['descuentos'], function ($descuento) use ($item) {
                    return $item['cantidad'] >= $descuento['cantidad_minima'];
                });

                if (!empty($descuentosAplicables)) {
                    // Encontrar el descuento máximo aplicable
                    $maxDescuento = max(array_column($descuentosAplicables, 'descuento'));

                    // Si este descuento es mejor que el actual (o no hay descuento combinado)
                    if ($maxDescuento > $item['descuento_aplicado']) {
                        $subtotal = $item['subtotal'];
                        $subtotalConDescuentoCliente = $subtotal * (1 - $descuentoCliente / 100);

                        $carrito[$key]['descuento_aplicado'] = $maxDescuento;
                        $carrito[$key]['descuento_aplicado_valor'] = $subtotalConDescuentoCliente * ($maxDescuento / 100);
                        $carrito[$key]['total_sin_iva'] = $subtotalConDescuentoCliente * (1 - $maxDescuento / 100);
                        $carrito[$key]['descuento_tipo'] = 'individual';
                    }
                }
            }
        }

        // Después de aplicar todos los descuentos, calculamos el IVA y el total final
        foreach ($carrito as $key => $item) {
            // El total_sin_iva ya tiene aplicados todos los descuentos
            $totalSinIva = $item['total_sin_iva'];

            // Calcular el IVA sobre el importe con todos los descuentos aplicados
            $carrito[$key]['iva_importe'] = $totalSinIva * ($ivaTasa / 100);

            // Calcular el total final con IVA
            $carrito[$key]['total'] = $totalSinIva + $carrito[$key]['iva_importe'];
        }

        return $carrito;
    }

    public function eliminarProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'index' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()]);
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$request->index])) {
            array_splice($carrito, $request->index, 1);

            // Recalcular los descuentos después de eliminar el producto
            $carrito = $this->calcularDescuentosCarrito($carrito);

            session()->put('carrito', $carrito);
            return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
        }

        return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
    }

    public function actualizarCarrito(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'index' => 'required|numeric',
            'cantidad' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()]);
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$request->index])) {
            $carrito[$request->index]['cantidad'] = $request->cantidad;
            session()->put('carrito', $carrito);

            // Recalcular los descuentos
            $carrito = $this->calcularDescuentosCarrito($carrito);
            session()->put('carrito', $carrito);

            return response()->json([
                'success' => true,
                'message' => 'Carrito actualizado',
                'item' => $carrito[$request->index]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
    }
}
