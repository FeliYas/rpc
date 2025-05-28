<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index()
    {
        $listas = Precio::orderby('orden', 'desc')->get();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('zonaprivada.lista', compact('logos', 'contactos', 'listas'));
    }
    public function ver($id)
    {
        $lista = Precio::findOrFail($id);
        $extension = strtolower(pathinfo($lista->lista, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            // Para archivos PDF, mantener la visualización directa
            return response()->file(storage_path('app/public/' . $lista->lista));
        } else if (in_array($extension, ['xls', 'xlsx'])) {
            // Para archivos Excel, redirigir a un visor específico
            $fileUrl = asset('storage/' . $lista->lista);

            // Opción 1: Usar Microsoft Office Online
            return redirect()->away('https://view.officeapps.live.com/op/view.aspx?src=' . urlencode($fileUrl));

            // Opción 2: Usar Google Sheets (alternativa)
            // return redirect()->away('https://docs.google.com/viewer?url=' . urlencode($fileUrl));
        } else {
            // Para otros tipos de archivos
            return response()->download(storage_path('app/public/' . $lista->lista));
        }
    }
    public function descargar($id)
    {
        $lista = Precio::findOrFail($id);
        return response()->download(storage_path('app/public/' . $lista->lista), $lista->nombre . '.' . pathinfo($lista->lista, PATHINFO_EXTENSION));
    }
    public function productos()
    {
        $categorias = Categoria::orderby('orden', 'desc')->get();
        $productos = Producto::orderby('orden', 'desc')->get();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('zonaprivada.productos', compact('categorias', 'logos', 'contactos', 'productos'));
    }
}
