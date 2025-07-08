@extends('layouts.dashboard')
@section('title', 'Empleados')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Empleados</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="[
                'nombre',
                'apellido',
                'cargo',
                'domicilio',
                'ciudad',
                'provincia',
                'pais',
                'telefono',
                'email',
                'valor_hora',
                'cantidad_horas',
                'en_blanco',
                'observaciones',
            ]" :data="$empleados" createRoute="empleados.store"
                updateRoute="empleados.update" deleteRoute="empleados.destroy" />
        </div>
    </div>

@endsection
