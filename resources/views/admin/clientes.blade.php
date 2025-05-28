@extends('layouts.dashboard')
@section('title', 'Clientes')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Clientes</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['usuario', 'email', 'telefono', 'cuit', 'direccion', 'direfiscal', 'provincia', 'localidad', 'rol', 'descuento', 'password', 'autorizado']" :data="$clientes" deleteRoute="clientes.destroy"
                updateRoute="clientes.update" />
        </div>
    </div>
@endsection
