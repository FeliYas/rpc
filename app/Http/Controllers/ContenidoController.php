<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contenido;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContenidoController extends Controller
{
    public function index()
    {
        $contenido = Contenido::first();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.contenido', compact('contenido', 'logo'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:100000', 
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|required',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $contenido = Contenido::findOrFail($id);

        $contenido->titulo = $request->titulo;
        $contenido->descripcion = $request->descripcion;

        if ($request->hasFile('path')) {
            if ($contenido->path && Storage::disk('public')->exists($contenido->path)) {
                Storage::disk('public')->delete($contenido->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $contenido->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $contenido->save();

        // Redirigir con un mensaje de éxito
        return $this->success_response('Contenido del home actualizado exitosamente.');
    }
}
