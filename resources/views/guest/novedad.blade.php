@extends('layouts.guest')
@section('title', 'Novedades')

@section('content')
    <div>
        <div class="h-[180px] lg:h-[250px] bg-gray-100">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block py-4">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('novedades') }}" class="hover:underline">Novedades</a>
                    <span class="mx-[5px]"></span>
                    <a href="" class="font-light hover:underline">{{ $novedad->titulo }}</a>
                </div>
                <div class="flex justify-center items-center h-full lg:mt-8">
                    <h1 class="text-2xl lg:text-[42px] font-bold text-center mt-20 lg:mt-0">Novedades</h1>

                </div>
            </div>
        </div>
        <div
            class="flex lg:flex-row flex-col justify-evenly items-start py-20 gap-10 max-w-[80%] xl:max-w-[1224px] mx-auto">
            <div class="lg:w-1/2">
                <img src="{{ asset('storage/' . $novedad->path) }}" alt="{{ $novedad->titulo }}"
                    class="w-full object-cover">
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-3xl font-semibold pb-4.5">{{ $novedad->titulo }}</h2>
                <div>
                    {!! $novedad->descripcion !!}
                </div>
            </div>
        </div>
    </div>
@endsection
