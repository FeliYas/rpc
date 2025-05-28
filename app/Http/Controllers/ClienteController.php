<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.clientes', compact('clientes', 'logo'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clientes',
            'telefono' => 'required|string|max:255',
            'cuit' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'direfiscal' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'localidad' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            // 🚨 Devolver error en formato JSON si la solicitud es AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first()
                ], 422);
            }

            return back()->with('error', $validator->messages()->first());
        }

        $validated = $validator->validated();

        Cliente::create([
            'usuario' => $validated['usuario'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'],
            'cuit' => $validated['cuit'],
            'direccion' => $validated['direccion'],
            'direfiscal' => $validated['direfiscal'],
            'provincia' => $validated['provincia'],
            'localidad' => $validated['localidad'],
            'password' => Hash::make($validated['password']),
        ]);

        // ✅ Respuesta AJAX en formato JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de registro recibida exitosamente.'
            ]);
        }

        return redirect()->back()->with('success', 'Solicitud de registro recibida exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $id,
            'telefono' => 'nullable|numeric',
            'direccion' => 'nullable|string|max:255',
            'direfiscal' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'localidad' => 'nullable|string|max:255',
            'rol' => 'required|in:cliente,vendedor,minorista,distribuidor',
            'descuento' => 'nullable|numeric',
            'password' => 'nullable|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        // Buscar el cliente a actualizar
        $cliente = Cliente::findOrFail($id);

        // Actualizar los campos del cliente
        $cliente->usuario = $request->input('usuario', $cliente->usuario);
        $cliente->email = $request->input('email', $cliente->email);
        $cliente->telefono = $request->input('telefono', $cliente->telefono);
        $cliente->direccion = $request->input('direccion', $cliente->direccion);
        $cliente->direfiscal = $request->input('direfiscal', $cliente->direfiscal);
        $cliente->provincia = $request->input('provincia', $cliente->provincia);
        $cliente->localidad = $request->input('localidad', $cliente->localidad);
        $cliente->rol = $request->input('rol', $cliente->rol);
        $cliente->descuento = $request->input('descuento', $cliente->descuento);
        $cliente->password = $request->filled('password') ? Hash::make($request->input('password')) : $cliente->password;

        // Guardar los cambios en la base de datos
        $cliente->save();
        return $this->success_response('Cliente actualizado exitosamente.');
    }
    public function destroy($id)
    {
        // Buscar el cliente a eliminar
        $cliente = Cliente::findOrFail($id);

        // Eliminar el cliente
        $cliente->delete();

        // Redirigir con un mensaje de éxito
        return $this->success_response('Usuario eliminado exitosamente.');
    }
    public function toggleAutorizado(Request $request)
    {
        $cliente = Cliente::findOrFail($request->id);
        $cliente->autorizado = $request->autorizado ? 1 : 0;
        $cliente->save();

        return response()->json(['success' => true]);
    }
}
