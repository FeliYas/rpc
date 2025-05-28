<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\PedidoRealizado;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Pedido; // Agregamos el modelo de Pedido
use Illuminate\Support\Facades\Storage;

class PedidoController extends Controller
{
    public function index(){
        $pedidos = Pedido::orderby('created_at', 'desc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.pedidosadm', compact('pedidos', 'logo'));
    }
    public function index2(){
        $pedidos = Pedido::orderby('created_at', 'desc')->get();
        return view('admin.pedidos', compact('pedidos'));
    }
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        // Eliminar la imagen del almacenamiento si es necesario
        if ($pedido->archivo && Storage::disk('public')->exists($pedido->archivo)) {
            Storage::disk('public')->delete($pedido->archivo);
        }
        // Eliminar el registro de la base de datos
        $pedido->delete();

        // Redirect or return response
        return $this->success_response('Pedido eliminado exitosamente.');
    }
    public function toggleCompletado(Request $request)
        {
            $pedido = Pedido::findOrFail($request->id);
            $pedido->completado = $request->completado ? 1 : 0;
            $pedido->save();

            return response()->json(['success' => true]);
        }
    public function procesar(Request $request)
    {
        // Validar datos del pedido
        $validator = Validator::make($request->all(), [
            'entrega' => 'required|in:retiro,transporte',
            'mensaje' => 'nullable|string',
            'archivo' => 'nullable|file|max:10240', // Máximo 10MB
            'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        // Verificar reCAPTCHA
        $recaptcha = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY', '6LfunycrAAAAAAUdd5QxBm7AeK_9ec2Phizdo6LA');
        $response = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $recaptcha
        );
        $responseKeys = json_decode($response, true);

        if (!$responseKeys["success"]) {
            return response()->json([
                'success' => false,
                'message' => 'Verificación de reCAPTCHA fallida, por favor intente nuevamente'
            ], 422);
        }

        // Obtener productos del carrito desde la sesión
        $productos = session('carrito', []);

        // Verificar que haya productos
        if (empty($productos)) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos en el pedido'
            ], 422);
        }

        // Obtener cliente en sesión
        $cliente = auth()->guard('cliente')->user();

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Debe iniciar sesión para realizar un pedido'
            ], 401);
        }

        // Calcular totales
        $subtotal = 0;
        $descuentoCliente = 0;
        $descuentoProductos = 0;
        $totalSinIva = 0;
        $ivaImporte = 0;
        $totalConIva = 0;
        $cantidadTotal = 0;

        foreach ($productos as $item) {
            $subtotal += $item['subtotal'] ?? 0;
            $descuentoCliente += $item['descuento_cliente_valor'] ?? 0;
            $descuentoProductos += $item['descuento_aplicado_valor'] ?? 0;
            $totalSinIva += $item['total_sin_iva'] ?? 0;
            $ivaImporte += $item['iva_importe'] ?? 0;
            $totalConIva += $item['total'] ?? 0;
            $cantidadTotal += $item['cantidad'] ?? 0;
        }

        // Manejar el archivo adjunto si existe
        $archivoPath = null;
        $nombreOriginal = null;
        if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
            $archivo = $request->file('archivo');
            $nombreOriginal = $archivo->getClientOriginalName();
            $nombreArchivo = time() . '_' . $nombreOriginal;
            $archivoPath = $archivo->storeAs('pedidos', $nombreArchivo, 'public');
        }

        // Crear nuevo registro en la tabla de pedidos
        $pedido = new Pedido();
        $pedido->cliente_id = $cliente->id;
        $pedido->productos = json_encode($productos); // Guardamos los productos como JSON
        $pedido->cantidad = $cantidadTotal;
        $pedido->completado = false;
        $pedido->entrega = $request->entrega == 'transporte'; // true para transporte, false para retiro
        $pedido->mensaje = $request->mensaje;
        $pedido->archivo = $archivoPath;
        $pedido->save();

        // Usamos el ID del pedido como número de pedido
        $numeroPedido = 'PED-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT);
        
        // Actualizamos el pedido con su número de referencia si es necesario
        // Si prefieres guardarlo en un campo separado, deberías añadir ese campo a la migración
        
        // Crear datos del pedido para el correo
        $datosPedido = [
            'productos' => $productos,
            'entrega' => $request->entrega,
            'mensaje' => $request->mensaje,
            'archivo' => [
                'path' => $archivoPath,
                'nombre' => $nombreOriginal
            ],
            'fecha' => now()->format('d/m/Y H:i'),
            'cliente' => $cliente,
            'numero_pedido' => $numeroPedido,
            'totales' => [
                'subtotal' => $subtotal,
                'descuento_cliente' => $descuentoCliente,
                'descuento_cliente_porcentaje' => $cliente->descuento,
                'descuento_productos' => $descuentoProductos,
                'total_sin_iva' => $totalSinIva,
                'iva_importe' => $ivaImporte,
                'total_con_iva' => $totalConIva
            ]
        ];

        // Obtener contactos para enviar el correo
        $contactos = Contacto::select('email')->whereNotNull('email')->get();
        $destinatarios = $contactos->pluck('email')->toArray();

        // Agregar el correo del cliente como destinatario
        if ($cliente->email) {
            $destinatarios[] = $cliente->email;
        }

        try {
            // Enviar correo con los datos del pedido
            Mail::to($destinatarios)->send(new PedidoRealizado($datosPedido));

            // Limpiar sesión
            session()->forget('carrito');

            // Guardar el número de pedido en la sesión temporalmente
            session()->flash('numero_pedido', $numeroPedido);

            return response()->json([
                'success' => true,
                'message' => 'Pedido enviado correctamente',
                'orderNumber' => $numeroPedido
            ]);
        } catch (\Exception $e) {
            // En caso de error con el envío del correo, eliminamos el pedido
            // O podemos mantenerlo y agregar un estado "error_envio" si prefieres
            // $pedido->delete();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmacionPedido($numero)
    {
        // Aquí podrías buscar el pedido en la base de datos
        // Extraer el ID del número de pedido (por ejemplo, si es PED-000123, obtener 123)
        $idPedido = 0;
        if (preg_match('/PED-(\d+)/', $numero, $matches)) {
            $idPedido = (int)$matches[1];
        }
        
        // Buscar el pedido si necesitas mostrarlo en la vista
        // $pedido = Pedido::find($idPedido);
        
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        
        return view('zonaprivada.pedido', [
            'orderNumber' => $numero,
            'logos' => $logos,
            'contactos' => $contactos
            // 'pedido' => $pedido // Si necesitas pasar el pedido a la vista
        ]);
    }
    public function getProductos($id)
    {
        // Obtener el pedido por ID
        $pedido = Pedido::findOrFail($id);
        
        // Verificar si el pedido tiene productos almacenados como JSON
        if (empty($pedido->productos)) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos asociados a este pedido',
                'productos' => []
            ]);
        }
        
        // Decodificar el JSON de productos
        try {
            // Si productos es ya un string JSON, lo decodificamos
            if (is_string($pedido->productos)) {
                $productos = json_decode($pedido->productos, true);
            } else {
                // Si ya es un array u objeto, lo usamos directamente
                $productos = $pedido->productos;
            }
            
            return response()->json([
                'success' => true,
                'productos' => $productos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al decodificar los productos',
                'error' => $e->getMessage(),
                'productos' => []
            ], 500);
        }
    }
}