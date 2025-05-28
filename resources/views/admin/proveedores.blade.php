@extends('layouts.dashboard')
@section('title', 'Proveedores')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Proveedores</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">        <div>
            <x-datatable-modal :columns="['dni', 'denominacion']" :data="$proveedores" createRoute="proveedores.store"
                updateRoute="proveedores.update" deleteRoute="proveedores.destroy" />
        </div>
    </div>

@endsection
