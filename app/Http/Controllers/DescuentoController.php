<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Descuento;
use App\Models\Logo;
use App\Models\Producto;
use App\Models\ProductoDescuento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DescuentoController extends Controller
{
    public function index()
    {
        $descuentos = Descuento::all();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.descuentos', compact('descuentos', 'logo'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'cantidad_minima' => 'required|string|min:1',
            'descuento' => 'required|string|min:1',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $descuento = Descuento::create([
            'nombre' => $request->nombre,
            'cantidad_minima' => $request->cantidad_minima,
            'descuento' => $request->descuento,
        ]);

        // Redirigir con mensaje de éxito
        return $this->success_response('Descuento creado exitosamente.');
    }
    public function update(Request $request, $id)
    {
        $descuento = Descuento::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'cantidad_minima' => 'required|string|min:1',
            'descuento' => 'required|string|min:1',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $descuento->update([
            'nombre' => $request->nombre,
            'cantidad_minima' => $request->cantidad_minima,
            'descuento' => $request->descuento,
        ]);

        // Redirigir con mensaje de éxito
        return $this->success_response('Descuento actualizado exitosamente.');
    }
    public function destroy($id)
    {
        $descuento = Descuento::findOrFail($id);
        $descuento->delete();

        // Redirigir con mensaje de éxito
        return $this->success_response('Descuento eliminado exitosamente.');
    }
    public function productos($id)
    {
        $descuento = Descuento::findOrFail($id);
        $productos = Producto::whereHas('descuentos', function ($query) use ($id) {
            $query->where('descuento_id', $id);
        })->get();

        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.participantes', compact('descuento', 'productos', 'logo'));
    }
    /**
     * Obtener productos que no están asociados al descuento especificado
     */
    public function getProductosDisponibles($descuentoId)
    {
        // Obtener IDs de productos ya asociados al descuento
        $productosAsociados = ProductoDescuento::where('descuento_id', $descuentoId)
            ->pluck('producto_id');

        // Obtener productos no asociados
        $productosDisponibles = Producto::whereNotIn('id', $productosAsociados)
            ->select('id', 'titulo')
            ->get();

        return response()->json($productosDisponibles);
    }

    /**
     * Almacenar múltiples productos seleccionados para un descuento
     */
    public function storeParticipantes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descuento_id' => 'required|exists:descuentos,id',
            'producto_ids' => 'required|array',
            'producto_ids.*' => 'exists:productos,id',
        ]);

        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        $descuentoId = $request->input('descuento_id');
        $productoIds = $request->input('producto_ids');

        $productosYaAsociados = [];

        // Verificar productos ya asociados
        foreach ($productoIds as $productoId) {
            $existe = ProductoDescuento::where('descuento_id', $descuentoId)
                ->where('producto_id', $productoId)
                ->exists();

            $codigo = Producto::where('id', $productoId)->value('codigo');

            if ($existe) {
                $productoNombre = Producto::find($productoId)->nombre ?? "codigo: $codigo";
                $productosYaAsociados[] = $productoNombre;
            }
        }

        // Si hay productos ya asociados, devolver error
        if (!empty($productosYaAsociados)) {
            $mensaje = count($productosYaAsociados) > 1
                ? 'Los productos: ' . implode(', ', $productosYaAsociados) . ' ya están asociados a este descuento.'
                : 'El producto con el ' . $productosYaAsociados[0] . ' ya está asociado a este descuento.';

            return $this->error_response($mensaje);
        }

        // Si no hay errores, asociar cada producto seleccionado con el descuento
        foreach ($productoIds as $productoId) {
            ProductoDescuento::create([
                'descuento_id' => $descuentoId,
                'producto_id' => $productoId
            ]);
        }

        return $this->success_response('Productos asociados correctamente al descuento.');
    }
    public function destroyParticipantes($productoId, Request $request)
    {
        $descuentoId = $request->input('descuento_id');

        try {
            $productoDescuento = ProductoDescuento::where('producto_id', $productoId)
                ->where('descuento_id', $descuentoId)
                ->firstOrFail();

            $productoDescuento->delete();

            return redirect()->route('participantes.dashboard', $descuentoId)
                ->with('success', 'Producto desasociado correctamente del descuento.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo desasociar el producto: ' . $e->getMessage());
        }
    }
}
