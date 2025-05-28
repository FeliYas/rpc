@extends('layouts.dashboard')
@section('title', 'Categorias')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Categorias</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['orden', 'path', 'titulo', 'destacado', 'adword']" :data="$categorias" deleteRoute="categorias.destroy"
                updateRoute="categorias.update" createRoute="categorias.store" recomendacion="380x380"/>
        </div>
    </div>
@endsection
