@extends('layouts.dashboard')
@section('title', 'Contenido Home')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Contenido Home</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div class="w-full flex flex-col gap-8 py-4">
            <form id='actualizar-contenido' action="{{ route('contenido.update', $contenido->id) }}" method="POST"
                enctype="multipart/form-data"
                class="w-full transition-all duration-300 hover:shadow-lg hover:border-main-color transform hover:-translate-y-1">
                @csrf
                @method('PUT')
                <div
                    class="w-full bg-gray-100 p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 group">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Contenedor de la imagen con vista previa -->
                        <div class="md:w-1/3 flex flex-col">
                            <h3
                                class="block text-sm font-medium text-gray-700 mb-2 group-hover:text-main-color transition-colors duration-300">
                                Imagen principal</h3>
                            <div
                                class="relative overflow-hidden rounded-lg border-2 border-gray-200 group-hover:border-main-color transition-all duration-300">
                                <img id="imagen-preview" src="{{ asset('storage/' . $contenido->path) }}" alt="Imagen"
                                    class="w-full h-auto object-cover rounded-md transition-all duration-500">

                                <!-- Overlay con efecto al hover -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 flex items-center justify-center transition-all duration-300 opacity-0 hover:bg-opacity-20 hover:opacity-100">
                                    <label for="path"
                                        class="cursor-pointer bg-white bg-opacity-80 text-main-color py-2 px-4 rounded-full flex items-center transform transition-transform duration-300 hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Cambiar imagen
                                    </label>
                                </div>
                            </div>

                            <!-- Recomendación de tamaño -->
                            <span class="text-xs text-gray-400 mt-2 italic">Recomendación: 670x600 px</span>

                            <!-- Input file oculto -->
                            <input type="file" class="hidden" id="path" name="path"
                                onchange="previewImage(this)">
                        </div>

                        <!-- Contenedor de formulario -->
                        <div class="flex flex-col justify-between w-full md:w-2/3">
                            <div class="flex flex-col gap-4">
                                <div class="relative group">
                                    <label for="titulo"
                                        class="block text-sm font-medium text-gray-700 mb-1 transition-colors duration-200 group-focus-within:text-main-color">Título</label>
                                    <input type="text" id="titulo" name="titulo" value="{{ $contenido->titulo }}"
                                        class="p-2 bg-white block border border-gray-300 w-full rounded-lg shadow-sm transition-all duration-200 focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20"
                                        required>
                                </div>

                                <div class="custom-container">
                                    <label for="descripcion"
                                        class="block text-sm font-medium text-gray-700 mb-1 transition-colors duration-200 group-focus-within:text-main-color">Descripción</label>
                                    <textarea name="descripcion" class="summernotep w-full" data-placeholder="Descripción del home...">{{ $contenido->descripcion }}</textarea>
                                </div>
                            </div>

                            <!-- Botón de actualizar -->
                            <div class="mb-6.5 mt-4">
                                <button type="submit" class="btn-primary w-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Actualizar contenido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para previsualizar la imagen
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagen-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {
            $('.summernotep').each(function() {
                let placeholderText = $(this).attr('data-placeholder') ||
                    'Escribí aquí...'; // Usa el placeholder o un valor por defecto

                $(this).summernote({
                    placeholder: placeholderText, // Asigna el placeholder correcto
                    tabsize: 2,
                    height: 200,
                    width: 1005,
                    zIndex: 0,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ]
                });
            });
        });
    </script>
@endsection
