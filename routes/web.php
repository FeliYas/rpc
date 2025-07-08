<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogosController;
use App\Http\Controllers\MetadatosController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\NovedadesController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ZonaController;
use App\Models\Logo;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/nosotros', [NosotrosController::class, 'show'])->name('nosotros');
Route::get('/productos', [ProductosController::class, 'show'])->name('categorias');
Route::get('/productos/{id}', [ProductosController::class, 'showProductos'])->name('productos');
Route::get('/productos/{id}/{producto}', [ProductosController::class, 'showProducto'])->name('producto');
Route::get('/novedades', [NovedadesController::class, 'show'])->name('novedades');
Route::get('/novedades/{id}', [NovedadesController::class, 'showNovedad'])->name('novedad');
Route::get('/contacto', [ContactoController::class, 'show'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');
Route::post('/cliente/register', [ClienteController::class, 'store'])->name('cliente.store');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.store');


// Ruta del login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Ruta del dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/adm', function () {
        $logo = Logo::where('seccion', 'footer')->first();
        return view('admin.dashboard', compact('logo'));
    })->name('dashboard');
});

Route::post('/zona/login', [ClienteAuthController::class, 'login'])->name('login.zonaprivada');
Route::post('/zona/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');

Route::middleware(['cliente'])->group(function () {
    Route::get('/zona', [ZonaController::class, 'index'])->name('zonaprivada');
    Route::get('/zonaprivada/ver/{id}', [ZonaController::class, 'ver'])->name('zonaprivada.ver');
    Route::get('/zonaprivada/descargar/{id}', [ZonaController::class, 'descargar'])->name('zonaprivada.descargar');

    Route::get('/zonaprivada/productos', [ZonaController::class, 'productos'])->name('productos.zonaprivada');
    Route::get('/zonaprivada/carrito', [CarritoController::class, 'zona'])->name('carrito.zonaprivada');
    Route::post('/zonaprivada/agregar-al-carrito', [CarritoController::class, 'agregarProducto'])->name('carrito.agregar');
    Route::post('/zonaprivada/actualizar-carrito', [CarritoController::class, 'actualizarCarrito'])->name('carrito.actualizar');
    Route::post('/zonaprivada/eliminar-del-carrito', [CarritoController::class, 'eliminarProducto'])->name('carrito.eliminar');
    Route::post('/zona-privada/pedido/procesar', [PedidoController::class, 'procesar'])->name('pedido.procesar');
    Route::get('/pedido/confirmacion/{numero}', [PedidoController::class, 'confirmacionPedido'])->name('pedido.confirmacion');
});


Route::middleware(['auth', 'verified'])->group(function () {
    //rutas del home del dashboard
    Route::get('/dashboard/home/slider', [SliderController::class, 'index'])->name('slider.dashboard');
    Route::post('/dashboard/home/slider/store', [SliderController::class, 'store'])->name('slider.store');
    Route::get('/slider/{id}/edit', [SliderController::class, 'edit'])->name('slider.edit');
    Route::put('/dashboard/home/slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('/dashboard/home/slider/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    Route::get('/dashboard/home/contenido', [ContenidoController::class, 'index'])->name('contenido.dashboard');
    Route::put('/dashboard/home/contenido/update/{id}', [ContenidoController::class, 'update'])->name('contenido.update');

    //rutas de las novedades del frontend
    Route::get('/dashboard/nosotros', [NosotrosController::class, 'index'])->name('nosotros.dashboard');
    Route::put('/dashboard/nosotros/update/{id}', [NosotrosController::class, 'update'])->name('nosotros.update');
    Route::put('/dashboard/nosotros/{id}/tarjeta/{num}/update', [NosotrosController::class, 'updateCard'])->name('tarjetanos.update');

    Route::get('/dashboard/productos/categorias', [CategoriasController::class, 'index'])->name('categorias.dashboard');
    Route::post('/dashboard/productos/categorias/store', [CategoriasController::class, 'store'])->name('categorias.store');
    Route::put('/dashboard/productos/categorias/update/{id}', [CategoriasController::class, 'update'])->name('categorias.update');
    Route::delete('/dashboard/productos/categorias/delete/{id}', [CategoriasController::class, 'destroy'])->name('categorias.destroy');
    Route::post('/dashboard/productos/categorias/destacado', [CategoriasController::class, 'toggleDestacado'])->name('categorias.toggleDestacado');
    Route::get('/dashboard/productos/producto', [ProductosController::class, 'index'])->name('productos.dashboard');
    Route::post('/dashboard/productos/producto/store', [ProductosController::class, 'store'])->name('productos.store');
    Route::put('/dashboard/productos/producto/update/{id}', [ProductosController::class, 'update'])->name('productos.update');
    Route::delete('/dashboard/productos/producto/delete/{id}', [ProductosController::class, 'destroy'])->name('productos.destroy');

    Route::get('/dashboard/productos/producto/caracteristicas/{id}', [ProductosController::class, 'caracteristicas'])->name('caracteristicas.dashboard');
    Route::post('/dashboard/productos/producto/caracteristicas/store', [ProductosController::class, 'storeCaracteristicas'])->name('caracteristicas.store');
    Route::put('/dashboard/productos/producto/caracteristicas/update/{id}', [ProductosController::class, 'updateCaracteristicas'])->name('caracteristicas.update');
    Route::delete('/dashboard/productos/producto/caracteristicas/delete/{id}', [ProductosController::class, 'destroyCaracteristicas'])->name('caracteristicas.destroy');

    Route::get('/dashboard/productos/producto/imagenes/{id}', [ProductosController::class, 'imagenes'])->name('imagenes.dashboard');
    Route::post('/dashboard/productos/producto/imagenes/store', [ProductosController::class, 'storeImagenes'])->name('imagenes.store');
    Route::put('/dashboard/productos/producto/imagenes/update/{id}', [ProductosController::class, 'updateImagenes'])->name('imagenes.update');
    Route::delete('/dashboard/productos/producto/imagenes/delete/{id}', [ProductosController::class, 'destroyImagenes'])->name('imagenes.destroy');

    //rutas de las novedades del dashboard
    Route::get('dashboard/novedades', [NovedadesController::class, 'index'])->name('novedades.dashboard');
    Route::post('dashboard/novedades/', [NovedadesController::class, 'store'])->name('novedades.store');
    Route::get('/novedades/{novedad}/edit', [NovedadesController::class, 'edit'])->name('novedades.edit');
    Route::put('dashboard/novedades/{id}', [NovedadesController::class, 'update'])->name('novedades.update');
    Route::delete('dashboard/novedades/{id}', [NovedadesController::class, 'destroy'])->name('novedades.destroy');

    //rutas del contacto del dashboard
    Route::get('/dashboard/contacto', [ContactoController::class, 'index'])->name('contacto.dashboard');
    Route::put('/dashboard/contacto/update/{id}', [ContactoController::class, 'update'])->name('contacto.update');

    //rutas de la zonaprivada del dashboard
    Route::get('/dashboard/zonaprivada/clientes', [ClienteController::class, 'index'])->name('clientes.dashboard');
    Route::put('/dashboard/zonaprivada/clientes/update/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/dashboard/zonaprivada/clientes/delete/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::post('/dashboard/productos/clientes/autorizado', [ClienteController::class, 'toggleAutorizado'])->name('clientes.toggleAutorizado');
    Route::get('/dashboard/zonaprivada/carrito', [CarritoController::class, 'index'])->name('carrito.dashboard');
    Route::put('/dashboard/zonaprivada/carrito/update/{id}', [CarritoController::class, 'update'])->name('carrito.update');
    Route::get('/dashboard/zonaprivada/lista-de-precios', [PrecioController::class, 'index'])->name('precios.dashboard');
    Route::post('/dashboard/zonaprivada/lista-de-precios/store', [PrecioController::class, 'store'])->name('precios.store');
    Route::put('/dashboard/zonaprivada/lista-de-precios/update/{id}', [PrecioController::class, 'update'])->name('precios.update');
    Route::delete('/dashboard/zonaprivada/lista-de-precios/delete/{id}', [PrecioController::class, 'destroy'])->name('precios.destroy');
    Route::get('/dashboard/zonaprivada/descuentos', [DescuentoController::class, 'index'])->name('descuentos.dashboard');
    Route::post('/dashboard/zonaprivada/descuentos/store', [DescuentoController::class, 'store'])->name('descuentos.store');
    Route::put('/dashboard/zonaprivada/descuentos/update/{id}', [DescuentoController::class, 'update'])->name('descuentos.update');
    Route::delete('/dashboard/zonaprivada/descuentos/delete/{id}', [DescuentoController::class, 'destroy'])->name('descuentos.destroy');
    Route::get('/dashboard/zonaprivada/descuentos/{id}/productos', [DescuentoController::class, 'productos'])->name('participantes.dashboard');
    Route::post('/dashboard/zonaprivada/descuentos/productos/store', [DescuentoController::class, 'storeParticipantes'])->name('participantes.store');
    Route::delete('/dashboard/zonaprivada/descuentos/productos/delete/{id}', [DescuentoController::class, 'destroyParticipantes'])->name('participantes.destroy');
    Route::get('/api/productos/disponibles/{descuentoId}', [DescuentoController::class, 'getProductosDisponibles']);
    Route::get('/dashboard/zonaprivada/pedidos', [PedidoController::class, 'index'])->name('pedidos.dashboard');

    //rutas de los proveedores del dashboard
    Route::get('/dashboard/proveedores', [ProveedorController::class, 'index'])->name('proveedores.dashboard');
    Route::post('/dashboard/proveedores/store', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::put('/dashboard/proveedores/update/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/dashboard/proveedores/delete/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');

    //rutas de las facturaciones del dashboard
    Route::get('/dashboard/facturas', [FacturaController::class, 'index'])->name('facturas.dashboard');
    Route::post('/dashboard/facturas/store', [FacturaController::class, 'store'])->name('facturas.store');
    Route::put('/dashboard/facturas/update/{id}', [FacturaController::class, 'update'])->name('facturas.update');
    Route::delete('/dashboard/facturas/delete/{id}', [FacturaController::class, 'destroy'])->name('facturas.destroy');
    Route::get('/dashboard/facturas/imprimir/{id}', [FacturaController::class, 'imprimirPDF'])->name('facturas.imprimir');
    Route::post('/dashboard/facturas/imprimir-multiples', [FacturaController::class, 'imprimirMultiplesPDF'])->name('facturas.imprimir.multiples');
    Route::post('/dashboard/facturas/resumen-pdf', [FacturaController::class, 'resumenPDF'])->name('facturas.resumen.pdf');

//rutas de los empleados del dashboard
    Route::get('/dashboard/empleados', [EmpleadoController::class, 'index'])->name('empleados.dashboard');
    Route::post('/dashboard/empleados/store', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::put('/dashboard/empleados/update/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/dashboard/empleados/delete/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

    //rutas de los logos del dashboard
    Route::get('/dashboard/logos', [LogosController::class, 'index'])->name('logos.dashboard');
    Route::put('/dashboard/logos/update/{id}', [LogosController::class, 'update'])->name('logos.update');

    //rutas del newsletter del dashboard
    Route::get('/dashboard/newsletter', [NewsletterController::class, 'index'])->name('newsletter.dashboard');
    Route::delete('/dashboard/newsletter/{id}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');

    //rutas de usuarios del dashboard
    Route::get('dashboard/usuarios', [UsuariosController::class, 'index'])->name('usuarios.dashboard');
    Route::post('dashboard/usuarios/create', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::put('dashboard/usuarios/edit/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('dashboard/usuarios/delete/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

    //rutas de los metadatos
    Route::get('dashboard/metadatos', [MetadatosController::class, 'index'])->name('metadatos.dashboard');
    Route::put('dashboard/metadatos/{id}', [MetadatosController::class, 'update'])->name('metadatos.update');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index2'])->name('pedidos');
    Route::get('/api/pedidos/{id}/productos', [PedidoController::class, 'getProductos']);
    Route::delete('/pedidos/delete/{id}', [PedidoController::class, 'destroy'])->name('pedidos.eliminar');
    Route::post('/pedidos/completado', [PedidoController::class, 'toggleCompletado'])->name('pedidos.toggleCompletado');
});