@extends('layouts.dashboard')
@section('title', 'Pedidos')

@section('content')
    <div class="group relative h-full w-full">
        <div class="py-3 text-xl text-gray-700 w-full">
            <h1>Pedidos</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['Numero de pedido', 'Email cliente', 'Metodo de entrega', 'mensaje', 'completado']" :data="$pedidos" deleteRoute="pedidos.eliminar" pedidoInfo="si"/>
        </div>
    </div>
@endsection
