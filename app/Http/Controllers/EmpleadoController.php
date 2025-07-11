<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::orderBy('apellido', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.empleados', compact('empleados', 'logo'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'domicilio' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'valor_hora' => 'required|numeric|min:0',
            'cantidad_horas' => 'required|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        
        // Crear el empleado con los datos validados
        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cargo' => $request->cargo,
            'domicilio' => $request->domicilio,
            'ciudad' => $request->ciudad,
            'provincia' => $request->provincia,
            'pais' => $request->pais,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'valor_hora' => $request->valor_hora,
            'cantidad_horas' => $request->cantidad_horas,
            'observaciones' => $request->observaciones,
        ]);
        
        return $this->success_response('Empleado creado exitosamente.');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'nullable|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'domicilio' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'valor_hora' => 'nullable|numeric|min:0',
            'cantidad_horas' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        
        // Actualizar el empleado con los datos validados
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cargo' => $request->cargo,
            'domicilio' => $request->domicilio,
            'ciudad' => $request->ciudad,
            'provincia' => $request->provincia,
            'pais' => $request->pais,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'valor_hora' => $request->valor_hora,
            'cantidad_horas' => $request->cantidad_horas,
            'observaciones' => $request->observaciones,
        ]);
        
        return $this->success_response('Empleado actualizado exitosamente.');
    }
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return $this->success_response('Empleado eliminado exitosamente.');
    }
     public function toggleBlanco(Request $request)
    {
        $empleado = Empleado::findOrFail($request->id);
        $empleado->en_blanco = $request->en_blanco ? 1 : 0;
        $empleado->save();

        return response()->json(['success' => true]);
    }
}
