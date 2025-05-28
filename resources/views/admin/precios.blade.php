@extends('layouts.dashboard')
@section('title', 'Lista de precios')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Lista de precios</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['orden', 'lista', 'titulo']" :data="$listas" deleteRoute="precios.destroy"
                updateRoute="precios.update" createRoute="precios.store" />
        </div>
    </div>
@endsection
