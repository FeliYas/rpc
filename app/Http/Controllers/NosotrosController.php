<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Nosotros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NosotrosController extends Controller
{
    public function show()
    {
        $nosotros = Nosotros::first();
        $metadatos = Metadato::where('seccion', 'nosotros')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('guest.nosotros', compact('nosotros', 'metadatos', 'logos', 'contactos'));
    }
    public function index()
    {
        $nosotros = Nosotros::first();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.nosotros', compact('nosotros', 'logo'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:100000', // Cambié 'required' a 'nullable'
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $nosotros = Nosotros::findOrFail($id);

        $nosotros->titulo = $request->titulo;
        $nosotros->descripcion = $request->descripcion;

        if ($request->hasFile('path')) {
            if ($nosotros->path && Storage::disk('public')->exists($nosotros->path)) {
                Storage::disk('public')->delete($nosotros->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $nosotros->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $nosotros->save();

        return $this->success_response('Contenido actualizado exitosamente.');
    }
    public function updateCard(Request $request, $id, $num)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $nosotros = Nosotros::findOrFail($id);

        // Concatenar 'titulo' y 'descripcion' con el número dinámico
        $campoTitulo = 'titulo' . $num;
        $campoDescripcion = 'descripcion' . $num;

        $nosotros->$campoTitulo = $request->titulo;
        $nosotros->$campoDescripcion = $request->descripcion;

        $nosotros->save();

        return $this->success_response('Tarjeta actualizada exitosamente.');
    }
}
