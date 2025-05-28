@extends('layouts.dashboard')
@section('title', 'Novedades')

@section('content')
    <div class="group h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Novedades</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        {{-- Mostrar Novedades existentes --}}
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
                @foreach ($novedades as $novedad)
                    <x-tarjeta-novedades :novedad="$novedad" deleteRoute="novedades.destroy" updateRoute="novedades.update" />
                @endforeach
            </div>
        </div>
        <div id="createModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
            <!-- Overlay con animación -->
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
                onclick="closeModal('createModalWrapper')" id="modalOverlay"></div>

            <!-- Modal con animación -->
            <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
                id="createModal">
                <form action="{{ route('novedades.store') }}" method="POST"
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
                            Crear Novedad
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
                                placeholder="Título de la novedad">
                        </div>

                        <!-- Campo Descripción -->
                        <div class="mb-4 custom-container">
                            <label for="descripcion" class="block text-gray-700 text-mg">Descripcion</label>
                            <textarea name="descripcion" class="summernote" data-placeholder="Descripcion en español..."></textarea>
                        </div>

                        <!-- Campo Imagen (Path) -->
                        <div class="mb-4">
                            <label for="path" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                            <div class="relative w-full">
                                <label for="path" id="customFileLabel"
                                    class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                    Elija una nueva imagen
                                </label>
                                <input type="file" id="path" name="path" class="hidden"
                                    onchange="updateFileLabel(this)">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM,
                                OGG. Recomendacion: 360x250 px </p>

                            <!-- Vista previa de la imagen -->
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
                            Editar Novedad
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
                            <label for="edit_descripcion" class="block text-gray-700 text-mg">Descripcion</label>
                            <textarea name="descripcion" id="edit_descripcion" class="summernote" data-placeholder="Descripcion en español..."></textarea>
                        </div>

                        <!-- Imagen actual -->
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen actual</label>
                            <img id="edit_imagePreview" src="" alt="Imagen actual"
                                class="w-full h-[200px] border border-main-color rounded-md bg-gray-200 p-2">
                        </div>

                        <!-- Campo Imagen (Path) -->
                        <div class="mb-2">
                            <label for="edit_path" class="block text-sm font-medium text-gray-700 mb-1">Nueva imagen
                            </label>
                            <div class="relative w-full">
                                <label for="edit_path" id="editCustomFileLabel"
                                    class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                    Elija una nueva imagen
                                </label>
                                <input type="file" id="edit_path" name="path" class="hidden"
                                    onchange="updateEditFileLabel(this)">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM,
                                OGG. Recomendacion: 360x250 px </p>
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
            <form id="deleteForm" method="POST" class="bg-white rounded-lg shadow-lg w-full overflow-hidden"
                data-action-template="{{ route('novedades.destroy', '__ID__') }}">
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
                        eliminar esta novedad?</h2>
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
        // Añade al final de tu script existente
        $(document).ready(function() {
            // Detectar click en botones de edición
            $(document).on('click', '.edit-novedad-btn', function() {
                const novedadId = $(this).data('id');

                // Hacer una petición AJAX para obtener los datos de la novedad
                $.ajax({
                    url: '/novedades/' + novedadId + '/edit', // Ajusta la URL según tu ruta
                    type: 'GET',
                    success: function(data) {
                        // Configurar el formulario
                        $('#editForm').attr('action',
                            '{{ route('novedades.update', '__ID__') }}'.replace('__ID__',
                                novedadId));

                        // Llenar los campos
                        $('#edit_orden').val(data.orden);
                        $('#edit_titulo').val(data.titulo);

                        // Si estás usando summernote, actualiza el contenido
                        $('#edit_descripcion').summernote('code', data.descripcion);

                        // Actualizar la vista previa de la imagen
                        $('#edit_imagePreview').attr('src', '/storage/' + data.path);
                        $('#edit_imagePreview').attr('alt', data.titulo);

                        // Abrir el modal
                        openModal('editModalWrapper');
                    },
                    error: function(error) {
                        console.error('Error al obtener datos:', error);
                        alert('No se pudieron cargar los datos para editar');
                    }
                });
            });

            // Función para actualizar la etiqueta del archivo en el formulario de edición
            function updateEditFileLabel(input) {
                const label = document.getElementById('editCustomFileLabel');
                if (input.files.length > 0) {
                    label.textContent = input.files[0].name;
                } else {
                    label.textContent = 'Elija una nueva imagen';
                }
            }

            // Exponer la función globalmente
            window.updateEditFileLabel = updateEditFileLabel;
        });
        // Añade este código al final de tu script existente dentro del $(document).ready
        $(document).ready(function() {
            // Código existente para la edición...

            // Detectar click en botones de eliminación
            $(document).on('click', '.delete-novedad-btn', function() {
                const novedadId = $(this).data('id');

                // Configurar el formulario de eliminación
                const actionTemplate = $('#deleteForm').data('action-template');
                const deleteAction = actionTemplate.replace('__ID__', novedadId);
                $('#deleteForm').attr('action', deleteAction);

                // Abrir el modal de eliminación
                openModal('deleteModalWrapper');
            });
        });

        // Asegurémonos de que la función closeModal esté disponible globalmente
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

        // Asegurémonos de que la función openModal esté disponible globalmente
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
            } else {
                label.textContent = 'Elija una nueva imagen';
            }
        }
    </script>

@endsection
