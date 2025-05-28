<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Metadato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MetadatosController extends Controller
{
    public function index()
    {
        $meta = Metadato::all();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.metadatos', compact('meta', 'logo'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'seccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'keyword' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $meta = Metadato::find($id);
        $meta->seccion = $request->seccion;
        $meta->descripcion = $request->descripcion;
        $meta->keyword = $request->keyword;
        $meta->save();


        return $this->success_response('Metadato actualizado exitosamente.');
    }
}
