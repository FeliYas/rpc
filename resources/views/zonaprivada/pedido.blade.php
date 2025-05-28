@extends('layouts.guest')
@section('title', 'Pedido Confirmado')

@section('content')
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto py-12">
        <div class="text-center max-w-lg mx-auto px-4 py-10 bg-white shadow rounded-lg border border-gray-200">
            <!-- Icono de éxito -->
            <div class="mx-auto mb-6 rounded-full bg-green-100 w-20 h-20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-4">Pedido confirmado</h1>

            <p class="text-gray-600 mb-2">
                Su pedido <span class="font-semibold">#{{ $orderNumber }} </span>está en proceso, en las proximas horas nos
                estaremos comunicando.<br>
                <span class="font-semibold">Guardate el numero de pedido para futuras gestiones</span>
            </p>

            <p class="text-gray-600 mb-8">
                Si tienes alguna pregunta, no dudes en contactarnos.
            </p>

            <a href="{{ route('productos.zonaprivada') }}" class="btn-primary-home-largo inline-block">
                VOLVER A PRODUCTOS
            </a>
        </div>
    </div>
@endsection
