@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Nosotros')

@section('content')
    <div>
        <div class="h-[180px] lg:h-[250px] bg-gray-100">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block py-4">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('nosotros') }}" class="font-light hover:underline">Nosotros</a>
                    <span class="mx-[5px]"></span>
                </div>
                <div class="flex justify-center items-center h-full xl:mt-8">
                    <h1 class="text-2xl lg:text-[42px] font-bold text-center mt-20 lg:mt-0">Nosotros</h1>

                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-13.5 mb-5 lg:mb-0 lg:py-20">
            <img src="{{ asset('storage/' . $nosotros->path) }}" alt="Contenido de la pagina"
                class="lg:w-1/2 h-[500px] lg:h-[600px] object-cover">
            <div class="lg:w-1/2 pr-[5%] pl-[5%] lg:pl-[0%] lg:pr-[71px] mt-12 flex flex-col gap-4">
                <h2 class="titulo-home">{{ $nosotros->titulo }}</h2>
                <div class="custom-summernote text-gray-500">
                    <p>
                        {!! $nosotros->descripcion !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full bg-gray-100 flex flex-col justify-center items-center ">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto py-10">
                <h2 class="titulo-home pb-10 lg:pb-2">¿Por que elegirnos?</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 lg:gap-6 py-4 lg:py-16">
                    <x-tarjeta-nosotros :imagen="$nosotros->imagen1" :titulo="$nosotros->titulo1" :descripcion="$nosotros->descripcion1" />
                    <x-tarjeta-nosotros :imagen="$nosotros->imagen2" :titulo="$nosotros->titulo2" :descripcion="$nosotros->descripcion2" />
                    <x-tarjeta-nosotros :imagen="$nosotros->imagen3" :titulo="$nosotros->titulo3" :descripcion="$nosotros->descripcion3" />
                </div>
            </div>
        </div>
    </div>
@endsection
