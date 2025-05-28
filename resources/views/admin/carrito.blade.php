@extends('layouts.dashboard')
@section('title', 'Carrito')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Carrito</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div class="w-full flex flex-col gap-8 py-4 ">
            <form id='actualizar-carrito' action="{{ route('carrito.update', $carrito->id) }}" method="POST"
                enctype="multipart/form-data"
                class="w-full transition-all duration-300 hover:shadow-lg hover:border-main-color transform hover:-translate-y-1">
                @csrf
                @method('PUT')
                <div
                    class="w-full bg-gray-100 p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 group">
                    <div class="flex flex-col md:flex-row gap-8">

                        <!-- Contenedor de formulario -->
                        <div class="flex flex-col justify-between w-full gap-4">
                            <div class="flex flex-col gap-4">
                                <div class="custom-container">
                                    <label for="descripcion"
                                        class="block text-sm font-medium text-gray-700 mb-1 transition-colors duration-200 group-focus-within:text-main-color">Informacion
                                        importante</label>
                                    <textarea name="descripcion" class="summernote w-full" data-placeholder="Descripción">{{ $carrito->descripcion }}</textarea>
                                </div>
                            </div>


                            <!-- Botón de actualizar -->
                            <div class="mb-6.5">
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
        $(document).ready(function() {
            $('.summernote').each(function() {
                let placeholderText = $(this).attr('data-placeholder') ||
                    'Escribí aquí...'; // Usa el placeholder o un valor por defecto

                $(this).summernote({
                    placeholder: placeholderText, // Asigna el placeholder correcto
                    tabsize: 2,
                    height: 200,
                    width: 1540,
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
