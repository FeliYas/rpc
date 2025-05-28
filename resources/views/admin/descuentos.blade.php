@extends('layouts.dashboard')
@section('title', 'Descuentos')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Descuentos</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['nombre', 'cantidad_minima', 'descuento', 'productos participantes']" :data="$descuentos" createRoute="descuentos.store"
                updateRoute="descuentos.update" deleteRoute="descuentos.destroy" />
        </div>
    </div>

@endsection
