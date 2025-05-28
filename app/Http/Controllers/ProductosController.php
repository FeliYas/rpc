<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Caracteristica;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Producto;
use App\Models\Productosimg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductosController extends Controller
{
    public function show()
    {
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $metadatos = Metadato::where('seccion', 'productos')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('guest.categorias', compact('categorias', 'metadatos', 'logos', 'contactos'));
    }
    public function showProductos($id)
    {
        $productos = Producto::where('categoria_id', $id)->orderBy('orden', 'asc')->get();
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $categoria = Categoria::findOrFail($id);
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();
        return view('guest.productos', compact('productos', 'categorias', 'categoria', 'logos', 'contactos'));
    }
    public function showProducto($id, $producto)
    {
        $categoria = Categoria::findOrFail($id);
        $producto = Producto::findOrFail($producto);

        // Cargar las características
        $producto->load('caracteristicas');

        // Obtener productos relacionados (misma categoría, excluyendo el producto actual)
        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->take(3)  // Limitamos a 3 productos relacionados
            ->get();

        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefonouno', 'telefonodos')->get();

        return view('guest.producto', compact(
            'producto',
            'categorias',
            'categoria',
            'logos',
            'contactos',
            'productosRelacionados'
        ));
    }
    public function index()
    {
        $productos = Producto::orderBy('orden', 'asc')->get();
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.productos', compact('productos', 'logo', 'categorias'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'codigo' => 'required|string|unique:productos,codigo',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria_id' => 'required',
            'precio' => 'required|string|min:0',
            'unidad'=> 'required|string',
            'ficha' => 'nullable|mimes:pdf|max:2048',
            'adword' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $fichaPath = null;
        if ($request->hasFile('ficha')) {
            $ficha = $request->file('ficha');
            $fichaName = uniqid() . '.' . $ficha->getClientOriginalExtension();
            $fichaPath = $ficha->storeAs('fichas', $fichaName, 'public');
        }
        $producto = Producto::create([
            'orden' => $request->orden,
            'codigo' => $request->codigo,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'precio' => $request->precio,
            'unidad' => $request->unidad,
            'ficha' => $fichaPath,
            'adword' => $request->adword,
        ]);

        return $this->success_response('Producto creado exitosamente.');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'codigo' => 'required|string|unique:productos,codigo,' . $id,
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria_id' => 'required',
            'precio' => 'required|string|min:0',
            'unidad'=> 'required|string',
            'ficha' => 'nullable|mimes:pdf|max:2048',
            'adword' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $producto = Producto::find($id);

        if ($request->hasFile('ficha')) {
            if ($producto->ficha && Storage::disk('public')->exists($producto->ficha)) {
                Storage::disk('public')->delete($producto->ficha);
            }

            $ficha = $request->file('ficha');
            $fichaName = uniqid() . '.' . $ficha->getClientOriginalExtension();
            $fichaPath = $ficha->storeAs('fichas', $fichaName, 'public');
            $producto->ficha = $fichaPath;
        }

        // Solo se actualizan los campos si el request contiene un valor
        $producto->update(array_filter([
            'orden' => $request->orden,
            'codigo' => $request->codigo,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'precio' => $request->precio,
            'unidad' => $request->unidad,
            'adword' => $request->adword,
        ]));

        return $this->success_response('Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Find the Porducto by id
        $producto = Producto::findOrFail($id);
        // Eliminar la imagen del almacenamiento si es necesario
        if ($producto->ficha && Storage::disk('public')->exists($producto->ficha)) {
            Storage::disk('public')->delete($producto->ficha);
        }
        // Eliminar el registro de la base de datos
        $producto->delete();

        // Redirect or return response
        return $this->success_response('Producto y sus fotos eliminados exitosamente.');
    }
    public function caracteristicas($id)
    {
        $producto = Producto::findOrFail($id);
        $caracteristicas = Caracteristica::where('producto_id', $id)->orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.caracteristicas', compact('producto', 'caracteristicas', 'logo'));
    }
    public function imagenes($id)
    {
        $producto = Producto::findOrFail($id);
        $imagenes = Productosimg::where('producto_id', $id)->orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.imagenes', compact('producto', 'imagenes', 'logo'));
    }
    public function storeImagenes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'path' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
            'orden' => 'required|string|max:255',
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
        $imagen = Productosimg::create([
            'orden'              => $request->orden,
            'path'               => $imageName,
            'producto_id'        => $request->producto_id,
        ]);
        return $this->success_response('Imagenes creadas exitosamente.');
    }
    public function updateImagenes(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'path' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'orden' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $productoImg = Productosimg::findOrFail($id);
        if ($request->hasFile('path')) {
            if ($productoImg->path && Storage::disk('public')->exists($productoImg->path)) {
                Storage::disk('public')->delete($productoImg->path);
            }
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $imageName = $file->storeAs('images', $fileName, 'public');
            $productoImg->path = $imageName;
        }
        // Solo se actualizan los campos si el request contiene un valor
        $productoImg->update(array_filter([
            'orden' => $request->orden,
            'producto_id' => $request->producto_id,
        ]));
        return $this->success_response('Imagen actualizada exitosamente.');
    }
    public function destroyImagenes($id)
    {
        $productoImg = Productosimg::findOrFail($id);
        if ($productoImg->path && Storage::disk('public')->exists($productoImg->path)) {
            Storage::disk('public')->delete($productoImg->path);
        }
        $productoImg->delete();
        return $this->success_response('Imagen eliminada exitosamente.');
    }
    public function storeCaracteristicas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'orden' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'valor' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        $caracteristica = Caracteristica::create([
            'orden'              => $request->orden,
            'nombre'             => $request->nombre,
            'valor'              => $request->valor,
            'producto_id'        => $request->producto_id,
        ]);
        return $this->success_response('Caracteristica creada exitosamente.');
    }
    public function updateCaracteristicas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'orden' => 'nullable|string|max:255',
            'nombre' => 'nullable|string|max:255',
            'valor' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $caracteristica = Caracteristica::findOrFail($id);

        // Solo se actualizan los campos si el request contiene un valor
        $caracteristica->update(array_filter([
            'orden' => $request->orden,
            'producto_id' => $request->producto_id,
            'nombre' => $request->nombre,
            'valor' => $request->valor,
        ]));
        return $this->success_response('Caracteristica actualizada exitosamente.');
    }
    public function destroyCaracteristicas($id)
    {
        $caracteristica = Caracteristica::findOrFail($id);

        $caracteristica->delete();
        return $this->success_response('Caracteristica eliminada exitosamente.');
    }
}
