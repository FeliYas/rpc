@extends('layouts.dashboard')
@section('title', 'Productos')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Caracteristicas tecnicas de {{ $producto->titulo }}</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            
            <x-datatable-modal :columns="['orden', 'nombre', 'valor', 'producto_id']" :data="$caracteristicas" :productoid="$producto->id" deleteRoute="caracteristicas.destroy"
                updateRoute="caracteristicas.update" createRoute="caracteristicas.store"/>
        </div>

    </div>
@endsection
