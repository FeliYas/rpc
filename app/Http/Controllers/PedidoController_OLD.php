<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\PedidoRealizado;
use App\Models\Contacto;
use App\Models\Logo;

class PedidoController extends Controller
{
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

        foreach ($productos as $item) {
            $subtotal += $item['subtotal'] ?? 0;
            $descuentoCliente += $item['descuento_cliente_valor'] ?? 0;
            $descuentoProductos += $item['descuento_aplicado_valor'] ?? 0;
            $totalSinIva += $item['total_sin_iva'] ?? 0;
            $ivaImporte += $item['iva_importe'] ?? 0;
            $totalConIva += $item['total'] ?? 0;
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

        // Generar un número de pedido único
        $numeroPedido = 'PED-' . date('YmdHis') . '-' . rand(100, 9999);

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
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmacionPedido($numero)
    {
        
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('zonaprivada.pedido', [
            'orderNumber' => $numero,
            'logos' => $logos,
            'contactos' => $contactos
        ]);
    }
}
