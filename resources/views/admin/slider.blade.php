@extends('layouts.dashboard')
@section('title', 'Home Slider')

@section('content')
    <div class="group h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Home Slider</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        {{-- Mostrar Sliders existentes --}}
        <div
            class="bg-gray-100 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl mt-4 p-6">
            <div class="flex justify-start items-center mb-6">
                <button class="btn-primary flex items-center gap-2" onclick="openModal('createModalWrapper')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Agregar
                </button>
            </div>
            <div class="grid grid-cols-3 gap-6">
                @foreach ($sliders as $slider)
                    <div class="bg-gray-50 h-[493px] p-4 rounded-md">
                        @php
                            $extension = pathinfo($slider->path, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ asset('storage/' . $slider->path) }}" alt="{{ $slider->titulo }}"
                                class="object-cover w-full h-[246px]">
                        @elseif (in_array($extension, ['mp4', 'webm', 'ogg']))
                            <video class="object-cover w-full h-[246px]" controls>
                                <source src="{{ asset('storage/' . $slider->path) }}" type="video/{{ $extension }}">
                                Tu navegador no soporta el formato de video.
                            </video>
                        @endif

                        <div class="mt-6">
                            <div class="flex text-center items-start gap-4">
                                <h3 class="text-2xl font-medium">{{ $slider->titulo }}</h3>
                                <p class="text-gray-500 mt-1.5">{{ strtoupper($slider->orden) }}</p>
                            </div>

                            <div class="text-gray-500 line-clamp-3 h-[96px] py-6">{!! $slider->descripcion !!}</div>

                            <div class="flex gap-6 mt-4">
                                <button type="button" data-id="{{ $slider->id }}"
                                    class="edit-slider-btn hover:bg-orange-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <button type="button" data-id="{{ $slider->id }}"
                                    class="delete-slider-btn text-red-600 hover:bg-red-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modal de creación -->
        <div id="createModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
            <!-- Overlay con animación -->
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
                onclick="closeModal('createModalWrapper')" id="modalOverlay"></div>

            <!-- Modal con animación -->
            <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
                id="createModal">
                <form action="{{ route('slider.store') }}" method="POST"
                    class="bg-white rounded-lg shadow-lg w-full max-h-screen overflow-y-auto" enctype="multipart/form-data">
                    @csrf

                    <!-- Header -->
                    <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                        <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Crear Slider
                        </h2>
                    </div>

                    <div class="p-6">
                        <!-- Campo Orden -->
                        <div class="mb-4">
                            <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">
                                Orden *
                            </label>
                            <input name="orden" id="orden" type="text" required
                                class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                placeholder="Posición de orden">
                        </div>

                        <!-- Campo Título -->
                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                                Título *
                            </label>
                            <input name="titulo" id="titulo" type="text" required
                                class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                placeholder="Título del slider">
                        </div>

                        <!-- Campo Descripción -->
                        <div class="mb-4 custom-container">
                            <label for="descripcion" class="block text-gray-700 text-mg">Descripción</label>
                            <textarea name="descripcion" class="summernote" data-placeholder="Descripción en español..."></textarea>
                        </div>

                        <!-- Campo Archivo (Path) -->
                        <div class="mb-4">
                            <label for="path" class="block text-sm font-medium text-gray-700 mb-1">Archivo (Imagen o
                                Video)</label>
                            <div class="relative w-full">
                                <label for="path" id="customFileLabel"
                                    class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                    Elija un archivo
                                </label>
                                <input type="file" id="path" name="path" class="hidden"
                                    onchange="updateFileLabel(this)">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM,
                                OGG. Recomendacion: 1400x750 px </p>

                            <!-- Vista previa del archivo -->
                            <div class="mt-3 hidden" id="imagePreviewContainer">
                                <p class="block text-sm text-gray-700">Vista previa:</p>
                                <img id="createImagePreview" src="" alt="Vista previa de la imagen"
                                    class="mt-2 max-h-40 border border-main-color rounded-md bg-gray-200 p-2">
                            </div>
                        </div>
                    </div>

                    <!-- Footer con botones -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('createModalWrapper')"
                            class="btn-secondary px-4 py-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Crear
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de edición -->
        <div id="editModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
            <!-- Overlay con animación -->
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
                onclick="closeModal('editModalWrapper')" id="editModalOverlay"></div>

            <!-- Modal con animación -->
            <div class="relative w-full max-w-[700px] z-50 transition-all duration-300 transform scale-95 opacity-0"
                id="editModal">
                <form id="editForm" method="POST"
                    class="bg-white rounded-lg shadow-lg w-full max-h-screen overflow-y-auto"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Header -->
                    <div class="bg-main-color bg-opacity-10 px-6 py-2 border-b border-main-color border-opacity-20">
                        <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar Slider
                        </h2>
                    </div>

                    <!-- Formulario -->
                    <div class="p-4">
                        <!-- Campo Orden -->
                        <div class="flex mb-2 w-full gap-4">
                            <div class="w-1/2">
                                <label for="edit_orden" class="block text-sm font-medium text-gray-700 mb-1">
                                    Orden
                                </label>
                                <input name="orden" id="edit_orden" type="text"
                                    class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                            </div>

                            <!-- Campo Título -->
                            <div class="w-1/2">
                                <label for="edit_titulo" class="block text-sm font-medium text-gray-700 mb-1">
                                    Título
                                </label>
                                <input name="titulo" id="edit_titulo" type="text"
                                    class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                            </div>
                        </div>


                        <!-- Campo Descripción -->
                        <div class="mb-2 custom-container">
                            <label for="edit_descripcion" class="block text-gray-700 text-mg">Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="summernote" data-placeholder="Descripción en español..."></textarea>
                        </div>

                        <!-- Archivo actual -->
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Archivo actual</label>
                            <div id="current_file_preview"
                                class="w-full h-[200px] border border-main-color rounded-md bg-gray-200 p-2">
                                <!-- Aquí se mostrará la imagen o video según corresponda -->
                            </div>
                        </div>

                        <!-- Campo Archivo (Path) -->
                        <div class="mb-2">
                            <label for="edit_path" class="block text-sm font-medium text-gray-700 mb-1">Nuevo archivo
                            </label>
                            <div class="relative w-full">
                                <label for="edit_path" id="editCustomFileLabel"
                                    class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                    Elija un nuevo archivo
                                </label>
                                <input type="file" id="edit_path" name="path" class="hidden"
                                    onchange="updateEditFileLabel(this)">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM,
                                OGG. Recomendacion: 1400x750 px </p>
                        </div>
                    </div>

                    <!-- Footer con botones -->
                    <div class="px-4 py-3 bg-gray-50 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('editModalWrapper')"
                            class="btn-secondary px-4 py-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                        <button type="submit" class="btn-primary px-4 py-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de eliminación -->
    <div id="deleteModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('deleteModalWrapper')" id="deleteModalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="deleteModal">
            <form id="deleteForm" method="POST" class="bg-white rounded-lg shadow-lg w-full"
                data-action-template="{{ route('slider.destroy', '__ID__') }}">
                @csrf
                @method('DELETE')

                <!-- Header con icono de advertencia -->
                <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-center">
                    <div class="rounded-full bg-red-100 p-3 inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">¿Estás seguro de que querés
                        eliminar este slider?</h2>
                    <p class="text-gray-600 text-center mb-6">Esta acción no se puede deshacer.</p>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-center gap-4">
                    <button type="button" onclick="closeModal('deleteModalWrapper')"
                        class="btn-secondary px-5 py-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-white px-5 py-2 rounded cursor-pointer hover:bg-red-700 transition-colors duration-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Configuración de SummerNote y funciones de modal
        $(document).ready(function() {
            $('.summernote').each(function() {
                let placeholderText = $(this).attr('data-placeholder') ||
                    'Escribí aquí...'; // Usa el placeholder o un valor por defecto

                $(this).summernote({
                    placeholder: placeholderText, // Asigna el placeholder correcto
                    tabsize: 2,
                    height: 100,
                    zIndex: 0,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ]
                });
            });

            // Detectar click en botones de edición
            $(document).on('click', '.edit-slider-btn', function() {
                const sliderId = $(this).data('id');

                // Hacer una petición AJAX para obtener los datos del slider
                $.ajax({
                    url: '/slider/' + sliderId + '/edit', // Ajusta la URL según tu ruta
                    type: 'GET',
                    success: function(data) {
                        // Configurar el formulario
                        $('#editForm').attr('action',
                            '{{ route('slider.update', '__ID__') }}'.replace('__ID__',
                                sliderId));

                        // Llenar los campos
                        $('#edit_orden').val(data.orden);
                        $('#edit_titulo').val(data.titulo);

                        // Si estás usando summernote, actualiza el contenido
                        $('#edit_descripcion').summernote('code', data.descripcion);

                        // Limpiar contenedor de previsualización primero
                        $('#current_file_preview').empty();

                        // Determinar el tipo de archivo y mostrar la vista previa adecuada
                        const extension = data.path.split('.').pop().toLowerCase();
                        const fileUrl = '/storage/' + data.path;

                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                            // Es una imagen
                            $('#current_file_preview').html(`
                                <img src="${fileUrl}" alt="${data.titulo}" class="w-full h-full object-contain">
                            `);
                        } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
                            // Es un video
                            $('#current_file_preview').html(`
                                <video class="w-full h-full object-contain" controls>
                                    <source src="${fileUrl}" type="video/${extension}">
                                    Tu navegador no soporta el formato de video.
                                </video>
                            `);
                        }

                        // Abrir el modal
                        openModal('editModalWrapper');
                    },
                    error: function(error) {
                        console.error('Error al obtener datos:', error);
                        alert('No se pudieron cargar los datos para editar');
                    }
                });
            });

            // Detectar click en botones de eliminación
            $(document).on('click', '.delete-slider-btn', function() {
                const sliderId = $(this).data('id');

                // Configurar el formulario de eliminación
                const actionTemplate = $('#deleteForm').data('action-template');
                const deleteAction = actionTemplate.replace('__ID__', sliderId);
                $('#deleteForm').attr('action', deleteAction);

                // Abrir el modal de eliminación
                openModal('deleteModalWrapper');
            });
        });

        function openModal(id) {
            const wrapper = document.getElementById(id);
            wrapper.classList.remove('hidden');

            const modal = wrapper.querySelector('div[id$="Modal"]');
            if (modal) {
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'scale-95');
                    modal.classList.add('opacity-100', 'scale-100');
                }, 10); // delay para que la transición se dispare
            }
        }

        function closeModal(id) {
            const wrapper = document.getElementById(id);
            const modal = wrapper.querySelector('div[id$="Modal"]');
            if (modal) {
                modal.classList.add('opacity-0', 'scale-95');
                modal.classList.remove('opacity-100', 'scale-100');
            }
            setTimeout(() => {
                wrapper.classList.add('hidden');
            }, 300); // esperar que termine la transición
        }

        function updateFileLabel(input) {
            const label = document.getElementById('customFileLabel');
            if (input.files.length > 0) {
                label.textContent = input.files[0].name;

                // Si es una imagen, mostrar vista previa
                if (input.files[0].type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('imagePreviewContainer').classList.remove('hidden');
                        document.getElementById('createImagePreview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    // Si es un video, no mostrar vista previa
                    document.getElementById('imagePreviewContainer').classList.add('hidden');
                }
            } else {
                label.textContent = 'Elija un archivo';
                document.getElementById('imagePreviewContainer').classList.add('hidden');
            }
        }

        function updateEditFileLabel(input) {
            const label = document.getElementById('editCustomFileLabel');
            if (input.files.length > 0) {
                label.textContent = input.files[0].name;
            } else {
                label.textContent = 'Elija un nuevo archivo';
            }
        }
    </script>
@endsection
