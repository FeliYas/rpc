<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Contenido;
use App\Models\Logo;
use App\Models\Novedad;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $sliders = Slider::orderBy('orden', 'asc')->get();
        $categorias = Categoria::where('destacado', 1)->orderBy('orden', 'asc')->get();
        $contenido = Contenido::first();
        $novedades = Novedad::orderBy('orden', 'asc')->take(3)->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('welcome', compact('logos', 'sliders', 'categorias', 'contenido', 'novedades', 'contactos'));
    }
}
