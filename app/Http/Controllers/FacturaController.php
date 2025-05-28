<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\Logo;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::with('proveedor', 'detalles')->orderBy('fecha', 'desc')->get();
        $proveedores = Proveedor::orderBy('denominacion', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.facturas', compact('facturas', 'proveedores', 'logo'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:20',
            'puntoventa' => 'required|string|max:10',
            'nrofactura' => 'required|string|max:20',
            'proveedor_id' => 'required|exists:proveedores,id',
            'tipo_cambio' => 'required|numeric|min:0.0001',
            'gravado' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|in:21.00,10.50,27.00',
            'iva_monto' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'importe_total' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Crear la factura
            $factura = Factura::create([
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'puntoventa' => $request->puntoventa,
                'nrofactura' => $request->nrofactura,
                'proveedor_id' => $request->proveedor_id,
                'tipo_cambio' => $request->tipo_cambio,
                'importe_total' => $request->importe_total
            ]);

            // Crear el detalle de la factura
            FacturaDetalle::create([
                'factura_id' => $factura->id,
                'gravado' => $request->gravado,
                'iva_porcentaje' => $request->iva_porcentaje,
                'iva_monto' => $request->iva_monto,
                'subtotal' => $request->subtotal
            ]);

            DB::commit();

            return $this->success_response('Factura creada exitosamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al crear la factura: ' . $e->getMessage())
                ->withInput();
        }
    }
}
