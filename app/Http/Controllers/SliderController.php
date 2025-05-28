<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.slider', compact('sliders', 'logo'));
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'path' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:100000',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        $filePath = null;
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        }
        // Crear el slider con los datos validados
        $slider = Slider::create([
            'orden' => $request->orden,
            'path' => $filePath, // Guardamos la ruta completa desde 'storage'
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
        ]);

        return $this->success_response('Slider creado exitosamente.');
    }
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return response()->json($slider);
    }

    public function update(Request $request, $id)
    {
        
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'path' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:100000', // Cambié 'required' a 'nullable'
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        // Obtener el slider a actualizar
        $slider = Slider::findOrFail($id);

        // Actualizar los campos del slider
        $slider->orden = $request->input('orden');
        $slider->titulo = $request->input('titulo');
        $slider->descripcion = $request->input('descripcion');
        // Si hay una nueva imagen, manejar la subida
        if ($request->hasFile('path')) {
            // Eliminar la imagen anterior si existe
            if ($slider->path && Storage::disk('public')->exists($slider->path)) {
                Storage::disk('public')->delete($slider->path);
            }
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
            $slider->path = $filePath;
        }

        // Guardar los cambios en la base de datos
        $slider->save();


        return $this->success_response('Slider actualizado exitosamente.');
    }
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Eliminar la imagen del almacenamiento si es necesario
        if ($slider->path && Storage::disk('public')->exists($slider->path)) {
            Storage::disk('public')->delete($slider->path);
        }

        // Eliminar el registro de la base de datos
        $slider->delete();


        return $this->success_response('Slider eliminado exitosamente.');
    }
}
