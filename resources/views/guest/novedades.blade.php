@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Novedades')

@section('content')
    <div>
        <div class="h-[180px] lg:h-[250px] bg-gray-100">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block py-4">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('novedades') }}" class="font-light hover:underline">Novedades</a>
                    <span class="mx-[5px]"></span>
                </div>
                <div class="flex justify-center items-center h-full lg:mt-8">
                    <h1 class="text-2xl lg:text-[42px] font-bold text-center mt-20 lg:mt-0">Novedades</h1>

                </div>
            </div>
        </div>
        <div class="py-20 max-w-[80%] xl:max-w-[1224px] mx-auto mb-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 w-full gap-6">
                @foreach ($novedades as $novedad)
                    <x-tarjeta-novedades :novedad="$novedad" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
