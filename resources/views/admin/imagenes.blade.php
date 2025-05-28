@extends('layouts.dashboard')
@section('title', 'Productos')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Imagenes de {{ $producto->titulo }}</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        
        <div>
            <x-datatable-modal :columns="['orden', 'path', 'producto_id']" :data="$imagenes" :productoid="$producto->id" deleteRoute="imagenes.destroy"
                updateRoute="imagenes.update" createRoute="imagenes.store" recomendacion="280x280" />
        </div>

    </div>
@endsection
