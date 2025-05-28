@extends('layouts.guest')
@section('title', 'Productos')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection
@section('content')
    <div>
        <div class="h-[180px] lg:h-[250px] bg-gray-100">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block py-4">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('categorias') }}" class="font-light hover:underline">Productos</a>
                    <span class="mx-[5px]"></span>
                </div>
                <div class="flex justify-center items-center h-full lg:mt-8">
                    <h1 class="text-2xl lg:text-[42px] font-bold text-center mt-20 lg:mt-0">Productos</h1>

                </div>
            </div>
        </div>
        <div class="max-w-[80%] xl:max-w-[1224px] mx-auto py-20 mb-13">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($categorias as $categoria)
                    <div
                        class="w-full xl:h-[392px] relative group overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('productos', ['id' => $categoria->id]) }}" class="block w-full h-full">
                            <img src="{{ asset('storage/' . $categoria->path) }}" alt="{{ $categoria->titulo }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            <p
                                class="absolute bottom-0 left-0 right-0 text-black text-xl lg:text-[26px] font-medium text-center py-4 opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                                {{ strtoupper($categoria->titulo) }}
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
