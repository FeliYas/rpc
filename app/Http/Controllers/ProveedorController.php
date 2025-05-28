<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('denominacion', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.proveedores', compact('proveedores', 'logo'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|max:255',
            'denominacion' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        // Crear el proveedor con los datos validados
        $proveedor = Proveedor::create([
            'dni' => $request->dni,
            'denominacion' => $request->denominacion,
        ]);
        return $this->success_response('Proveedor creado exitosamente.');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|max:255',
            'denominacion' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        // Actualizar el proveedor con los datos validados
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update([
            'dni' => $request->dni,
            'denominacion' => $request->denominacion,
        ]);
        return $this->success_response('Proveedor actualizado exitosamente.');
    }
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();
        return $this->success_response('Proveedor eliminado exitosamente.');
    }
}
