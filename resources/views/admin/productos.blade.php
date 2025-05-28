@extends('layouts.dashboard')
@section('title', 'Productos')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Productos</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['orden', 'codigo', 'titulo', 'descripcion', 'precio', 'unidad', 'categoria_id', 'ficha', 'imagenes', 'caracteristicas']" :data="$productos" :categorias="$categorias" deleteRoute="productos.destroy"
                updateRoute="productos.update" createRoute="productos.store" />
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
                    height: 100,
                    width: 459,
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
