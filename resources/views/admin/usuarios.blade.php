@extends('layouts.dashboard')
@section('title', 'Usuarios')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Usuarios</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['name', 'email', 'password', 'role']" :data="$usuarios" deleteRoute="usuarios.destroy"
                updateRoute="usuarios.update" createRoute="usuarios.store" />
        </div>
    </div>
@endsection
