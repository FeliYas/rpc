<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LogosController extends Controller
{
    public function index()
    {
        $logos = Logo::all();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.logos', compact('logos', 'logo'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seccion' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $logo = Logo::find($id);
        if ($request->hasFile('path')) {
            if ($logo->path && Storage::disk('public')->exists($logo->path)) {
                Storage::disk('public')->delete($logo->path);
            }
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        } else {
            $filePath = $logo->path;
        }
        $logo->path = $filePath;
        $logo->seccion = $request->seccion ?? $logo->seccion;
        $logo->save();
        return $this->success_response('Logo actualizado exitosamente.');
    }
}
