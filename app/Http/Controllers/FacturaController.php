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
use Barryvdh\DomPDF\Facade\Pdf;

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
            'moneda' => 'required|in:ARS,USD',
            'tipo_cambio' => 'required_if:moneda,USD|nullable|numeric|min:0.0001',
            'gravado' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|in:21.00,10.50,27.00',
            'iva_monto' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'importe_total' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        try {
            DB::beginTransaction();

            $tipo_cambio = $request->moneda === 'USD' ? $request->tipo_cambio : 1;

            // Crear la factura
            $factura = Factura::create([
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'puntoventa' => $request->puntoventa,
                'nrofactura' => $request->nrofactura,
                'proveedor_id' => $request->proveedor_id,
                'moneda' => $request->moneda,
                'tipo_cambio' => $tipo_cambio,
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
            return $this->error_response('Error al crear la factura: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:20',
            'puntoventa' => 'required|string|max:10',
            'nrofactura' => 'required|string|max:20',
            'proveedor_id' => 'required|exists:proveedores,id',
            'moneda' => 'required|in:ARS,USD',
            'tipo_cambio' => 'required_if:moneda,USD|nullable|numeric|min:0.0001',
            'gravado' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|in:21.00,10.50,27.00',
            'iva_monto' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'importe_total' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        try {
            DB::beginTransaction();

            $factura = Factura::findOrFail($id);
            $tipo_cambio = $request->moneda === 'USD' ? $request->tipo_cambio : 1;

            $factura->update([
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'puntoventa' => $request->puntoventa,
                'nrofactura' => $request->nrofactura,
                'proveedor_id' => $request->proveedor_id,
                'moneda' => $request->moneda,
                'tipo_cambio' => $tipo_cambio,
                'importe_total' => $request->importe_total
            ]);

            $factura->detalles()->update([
                'gravado' => $request->gravado,
                'iva_porcentaje' => $request->iva_porcentaje,
                'iva_monto' => $request->iva_monto,
                'subtotal' => $request->subtotal
            ]);

            DB::commit();

            return $this->success_response('Factura actualizada exitosamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error_response('Error al actualizar la factura: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $factura = Factura::findOrFail($id);
            $factura->detalles()->delete();
            $factura->delete();

            DB::commit();

            return $this->success_response('Factura eliminada exitosamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error_response('Error al eliminar la factura: ' . $e->getMessage());
        }
    }

    public function imprimirPDF($id)
    {
        $factura = Factura::with('proveedor', 'detalles')->findOrFail($id);
        $logo = Logo::where('seccion', 'footer')->first();
        
        $pdf = Pdf::loadView('admin.pdf.factura', compact('factura', 'logo'));
        
        return $pdf->download('factura_' . $factura->puntoventa . '_' . $factura->nrofactura . '.pdf');
    }

    public function imprimirMultiplesPDF(Request $request)
    {
        $request->validate([
            'facturas' => 'required|array',
            'facturas.*' => 'exists:facturas,id'
        ]);

        $facturas = Factura::with('proveedor', 'detalles')
            ->whereIn('id', $request->facturas)
            ->get();
        
        $logo = Logo::where('seccion', 'footer')->first();
        
        $pdf = Pdf::loadView('admin.pdf.facturas-multiples', compact('facturas', 'logo'));
        
        return $pdf->download('facturas_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function resumenPDF(Request $request)
    {
        $request->validate([
            'facturas' => 'required|array',
            'facturas.*' => 'exists:facturas,id'
        ]);

        $facturas = Factura::with('proveedor', 'detalles')
            ->whereIn('id', $request->facturas)
            ->orderBy('fecha', 'desc')
            ->get();
        
        // Calcular totales siempre en ARS, convirtiendo USD según tipo de cambio individual
        $totalGravadoARS = 0;
        $totalIvaARS = 0;
        $totalGeneralARS = 0;
        
        foreach ($facturas as $factura) {
            if ($factura->detalles->isNotEmpty()) {
                $detalle = $factura->detalles->first();
                $tipoCambio = $factura->tipo_cambio ?? 1;
                
                // Si es USD, convertir a ARS usando el tipo de cambio individual
                if ($factura->moneda === 'USD') {
                    $totalGravadoARS += $detalle->gravado * $tipoCambio;
                    $totalIvaARS += $detalle->iva_monto * $tipoCambio;
                } else {
                    // Si es ARS, sumar directamente
                    $totalGravadoARS += $detalle->gravado;
                    $totalIvaARS += $detalle->iva_monto;
                }
            }
            
            // El importe total ya está en ARS (se calcula en el frontend)
            $totalGeneralARS += $factura->importe_total;
        }
        
        $totales = [
            'gravado_ars' => $totalGravadoARS,
            'iva_ars' => $totalIvaARS,
            'general_ars' => $totalGeneralARS,
            'cantidad' => $facturas->count()
        ];
        
        $logo = Logo::where('seccion', 'footer')->first();
        
        $pdf = Pdf::loadView('admin.pdf.resumen-facturas', compact('facturas', 'totales', 'logo'));
        
        return $pdf->download('resumen_facturas_' . date('Y-m-d_H-i-s') . '.pdf');
    }

}
