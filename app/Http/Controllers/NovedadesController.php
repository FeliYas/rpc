<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Novedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NovedadesController extends Controller
{
    public function show()
    {
        $novedades = Novedad::latest()->get();
        $metadatos = Metadato::where('seccion', 'novedades')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('guest.novedades', compact('novedades', 'metadatos', 'logos', 'contactos'));
    }
    public function showNovedad($id)
    {
        $novedad = Novedad::findOrFail($id);
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('guest.novedad', compact('novedad', 'logos', 'contactos'));
    }
    public function index()
    {
        $novedades = Novedad::orderby('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.novedades', compact('novedades', 'logo'));
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'required|string|unique:novedades,orden',
            'titulo'               => 'required|string|max:255',
            'descripcion'          => 'required|string',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        $imageName = null;
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $imageName = $file->storeAs('images', $fileName, 'public');
        }
        // Crear la novedad con los datos validados
        $novedad = Novedad::create([
            'orden'              => $request->orden,
            'titulo'             => $request->titulo,
            'descripcion'        => $request->descripcion,
            'path'               => $imageName,
        ]);

        // Redirigir con mensaje de éxito
        return $this->success_response('Novedad creada exitosamente.');
    }
    public function edit(Novedad $novedad)
    {
        return response()->json($novedad);
    }
    public function update(Request $request, $id)
    {
        
        // Validar los campos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'nullable|string',
            'titulo'               => 'nullable|string|max:255',
            'descripcion'          => 'nullable|string',
            'path'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        // Obtener el registro de Novedades
        $novedades = Novedad::findOrFail($id);

        // Manejar la actualización de la imagen principal (input "path")
        if ($request->hasFile('path')) {
            if ($novedades->path && Storage::disk('public')->exists($novedades->path)) {
                Storage::disk('public')->delete($novedades->path);
            }

            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        } else {
            $filePath = $novedades->path; // Mantener la imagen anterior si no se sube una nueva
        }
        $novedades->orden              = $request->orden;
        $novedades->titulo             = $request->titulo;
        $novedades->descripcion        = $request->descripcion;
        $novedades->path               = $filePath;
        // Guardar los cambios en Novedades
        $novedades->save();

        return $this->success_response('Novedad actualizada exitosamente.');
    }

    public function destroy($id)
    {
        // Find the Novedades by id
        $novedades = Novedad::findOrFail($id);

        // Eliminar la imagen del almacenamiento si es necesario
        if ($novedades->path && Storage::disk('public')->exists($novedades->path)) {
            Storage::disk('public')->delete($novedades->path);
        }

        // Eliminar el registro de la base de datos
        $novedades->delete();

        // Redirect or return response
        return $this->success_response('Novedad eliminada exitosamente.');
    }
}
