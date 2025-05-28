@extends('layouts.guest')
@section('title', 'Zona Privada')

@section('content')
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
        <div class="hidden lg:block h-[120px]">
            <div class="text-black py-4">
                <a href="{{ route('home') }}" class="hover:underline transition-all duration-300">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a href="" class="hover:underline transition-all duration-300 font-light">Lista de precios</a>
            </div>
        </div>

        <div class="py-4 mb-10 overflow-x-auto">
            <table class="w-full">
                <thead class="h-[71px]">
                    <tr class="bg-main-color text-white">
                        <th class="p-4 w-16"></th>
                        <th class="px-8 text-start font-medium">Nombre</th>
                        <th class="px-8 text-start font-medium">Formato</th>
                        <th class="px-8 text-start font-medium">Peso</th>
                        <th class="px-8 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listas as $lista)
                        <tr class="border-b border-gray-200 text-gray-600">
                            <td class="flex justify-center items-center text-center bg-gray-100 p-5 m-2">
                                @php
                                    $extension = pathinfo($lista->lista, PATHINFO_EXTENSION);
                                @endphp
                                <div class="">
                                    <svg width="43" height="43" viewBox="0 0 43 43" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M25.0837 3.58337V10.75C25.0837 11.7004 25.4612 12.6118 26.1332 13.2838C26.8052 13.9558 27.7166 14.3334 28.667 14.3334H35.8337M17.917 16.125H14.3337M28.667 23.2917H14.3337M28.667 30.4584H14.3337M26.8753 3.58337H10.7503C9.79997 3.58337 8.88853 3.9609 8.21653 4.63291C7.54452 5.30491 7.16699 6.21635 7.16699 7.16671V35.8334C7.16699 36.7837 7.54452 37.6952 8.21653 38.3672C8.88853 39.0392 9.79997 39.4167 10.7503 39.4167H32.2503C33.2007 39.4167 34.1121 39.0392 34.7841 38.3672C35.4561 37.6952 35.8337 36.7837 35.8337 35.8334V12.5417L26.8753 3.58337Z"
                                            stroke="#0D8141" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>

                            </td>
                            <td class="px-8 text-start">{{ $lista->titulo }}</td>
                            <td class="px-8 text-start">{{ strtoupper($extension) }}</td>
                            <td class="px-8 text-start">
                                @php
                                    $filePath = storage_path('app/public/' . $lista->lista);
                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                @endphp
                                <span>{{ number_format($fileSize / 1024, 0) }}kb</span>
                            </td>
                            <td class=" text-center">
                                <div class="flex justify-between items-center ">

                                    <div class="flex gap-6">
                                        <a href="{{ route('zonaprivada.ver', $lista->id) }}"
                                            class="btn-secondary-home font-medium">VER
                                            ONLINE</a>
                                        <a href="{{ route('zonaprivada.descargar', $lista->id) }}"
                                            class="flex items-center justify-center gap-1 bg-main-color border border-main-color transform transition-all duration-300 cursor-pointer text-white w-[184px] font-medium">DESCARGAR</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
