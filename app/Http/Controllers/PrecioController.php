<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Precio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PrecioController extends Controller
{
    public function index()
    {
        $listas = Precio::orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.precios', compact('listas', 'logo'));
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'lista' => 'mimes:pdf,xlsx,xls|max:2048'
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $listaName = null;
        if ($request->hasFile('lista')) {
            $file = $request->file('lista');
            $fileName = $file->getClientOriginalName(); // Use the original file name
            $listaName = $file->storeAs('fichas', $fileName, 'public');
        }
        // Crear la lista con los datos validados
        $lista = Precio::create([
            'orden'              => $request->orden,
            'titulo'             => $request->titulo,
            'lista'               => $listaName,
        ]);

        return $this->success_response('Lista de precios creada exitosamente.');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'nullable|string|max:255',
            'titulo' => 'nullable|string|max:255',
            'lista' => 'nullable|mimes:pdf,xlsx,xls|max:2048'
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $lista = Precio::findOrFail($id);

        $lista->titulo = $request->titulo;
        $lista->orden = $request->orden;

        if ($request->hasFile('lista')) {
            if ($lista->lista && Storage::disk('public')->exists($lista->lista)) {
                Storage::disk('public')->delete($lista->lista);
            }
            // Generar un nombre único para la nueva lista
            $fileName = $request->file('lista')->getClientOriginalName(); // Use the original file name

            // Mover la lista a la carpeta public/storage/fichas y obtener el nombre relativo
            $filePath = $request->file('lista')->storeAs('fichas', $fileName, 'public');

            // Actualizar la ruta de la file
            $lista->lista = 'fichas/' . $fileName; // Guardamos la ruta relativa de la file
        }
        $lista->save();

        // Redirigir con un mensaje de éxito
        return $this->success_response('Lista de precios actualizada exitosamente.');
    }
    public function destroy($id)
    {
        // Find the Lista de precios by id
        $lista = Precio::findOrFail($id);

        // Eliminar la lista del almacenamiento si es necesario
        if ($lista->lista && Storage::disk('public')->exists($lista->lista)) {
            Storage::disk('public')->delete($lista->lista);
        }

        // Eliminar el registro de la base de datos
        $lista->delete();

        // Redirect or return response
        return $this->success_response('Lista de precios eliminada exitosamente.');
    }
}
