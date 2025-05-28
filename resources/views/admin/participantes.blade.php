@extends('layouts.dashboard')
@section('title', 'Zona privada')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Productos participantes de {{ $descuento->nombre }}</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            
            <x-datatable-modal :columns="['orden', 'codigo', 'titulo']" :data="$productos"  :descuentoid="$descuento->id" deleteRoute="participantes.destroy" createRoute="participantes.store"/>
        </div>

    </div>
@endsection
