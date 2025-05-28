@extends('layouts.dashboard')
@section('title', 'Contacto')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Contacto</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div
            class="bg-gray-100 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl mt-4">
            <!-- Formulario con efectos de animación -->
            <form id="actualizar-contacto" action="{{ route('contacto.update', $contacto->id) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dirección con icono -->
                    <div class="relative group">
                        <div
                            class="absolute left-3 top-8 text-gray-400 transition-all duration-200 group-focus-within:text-main-color">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mt-0.5">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <label for="direccion"
                            class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Dirección</label>
                        <input type="text" id="direccion" name="direccion" value="{{ $contacto->direccion }}"
                            class="pl-10 p-2 bg-white block border border-gray-300 w-full h-10 rounded-lg shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20 transition-all duration-200"
                            required>
                    </div>

                    <!-- Email con icono -->
                    <div class="relative group">
                        <div
                            class="absolute left-3 top-8 text-gray-400 transition-all duration-200 group-focus-within:text-main-color">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mt-0.5">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Email</label>
                        <input type="email" id="email" name="email" value="{{ $contacto->email }}"
                            class="pl-10 p-2 bg-white block border border-gray-300 w-full h-10 rounded-lg shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20 transition-all duration-200"
                            required>
                    </div>

                    <!-- Teléfono uno con icono -->
                    <div class="relative group">
                        <div
                            class="absolute left-3 top-8 text-gray-400 transition-all duration-200 group-focus-within:text-main-color">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mt-0.5">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                        </div>
                        <label for="telefonouno"
                            class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Teléfono
                            principal</label>
                        <input type="tel" id="telefonouno" name="telefonouno" value="{{ $contacto->telefonouno }}"
                            class="pl-10 p-2 bg-white block border border-gray-300 w-full h-10 rounded-lg shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20 transition-all duration-200"
                            required>
                    </div>

                    <!-- Teléfono dos con icono -->
                    <div class="relative group">
                        <div
                            class="absolute left-3 top-8 text-gray-400 transition-all duration-200 group-focus-within:text-main-color">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mt-0.5">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                        </div>
                        <label for="telefonodos"
                            class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Teléfono
                            alternativo</label>
                        <input type="tel" id="telefonodos" name="telefonodos" value="{{ $contacto->telefonodos }}"
                            class="pl-10 p-2 bg-white block border border-gray-300 w-full h-10 rounded-lg shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20 transition-all duration-200"
                            required>
                    </div>
                </div>

                <!-- Iframe con icono -->
                <div class="relative group mt-6">
                    <div
                        class="absolute left-3 top-8 text-gray-400 transition-all duration-200 group-focus-within:text-main-color">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mt-0.5">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <label for="iframe"
                        class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Mapa
                        (iframe)</label>
                    <input type="text" id="iframe" name="iframe" value="{{ $contacto->iframe }}"
                        class="pl-10 p-2 bg-white block border border-gray-300 w-full h-10 rounded-lg shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20 transition-all duration-200"
                        required>
                </div>

                <!-- Vista previa del mapa si hay iframe -->
                <div class="mt-6 rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md"
                    id="map-preview">
                    {!! preg_replace(['/width="[^"]*"/', '/height="[^"]*"/'], ['width="100%"', 'height="100%"'], $mapa) !!}
                </div>

                <!-- Botones con efectos de hover -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit" class="btn-primary flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-1">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-pulse {
                animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }
        </style>
    </div>

@endsection
