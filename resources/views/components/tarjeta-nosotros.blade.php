@props(['editRoute', 'imagen', 'titulo', 'descripcion', 'id', 'num'])

@if (isset($editRoute))
    <form method="POST" action="{{ route($editRoute, ['id' => $id, 'num' => $num]) }}"
        class="bg-gray-50 p-6 border border-gray-200 rounded-lg shadow-md flex flex-col w-full mb-4 transition-all duration-300 hover:shadow-lg hover:border-main-color transform hover:-translate-y-1">
        @csrf
        @method('PUT')

        <div class="group mb-6">
            <div
                class="rounded-full bg-white p-2 shadow-md transition-all duration-300 group-hover:shadow-main-color/30">
                <div
                    class="bg-main-color rounded-full p-3 transition-all duration-300 group-hover:bg-white group-hover:shadow-inner">
                    <img src="{{ asset('storage/' . $imagen) }}" alt="Icon"
                        class="w-8 h-8 object-contain transition-all duration-300">
                </div>
            </div>
        </div>

        <div class="w-full flex-1 py-2">
            <div class="mb-4 relative group">
                <label for="titulo{{ $num }}"
                    class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Título</label>
                <div class="relative">
                    <input type="text" id="titulo{{ $num }}" name="titulo" value="{{ $titulo }}"
                        class="p-2 pl-3 bg-white block border border-gray-300 w-full h-10 rounded-lg shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20 transition-all duration-200"
                        required>
                    <div
                        class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="custom-container mb-4 relative group">
                <label for="descripcion{{ $num }}"
                    class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-200 group-focus-within:text-main-color">Descripción</label>
                <textarea id="descripcion{{ $num }}" name="descripcion" class="summernote w-full"
                    data-placeholder="Escribe una descripción...">
                    {{ old('descripcion', $descripcion) }}
                </textarea>
            </div>
        </div>

        <div class="w-full flex justify-end mt-4">
            <button type="submit" class="btn-primary flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="mr-1">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Guardar cambios
            </button>
        </div>
    </form>
@else
    <div
        class="relative bg-white px-8 py-8 shadow-md flex flex-col items-start text-start transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 group h-[390px]">
        <div class="absolute -top-8">
                <div
                    class="bg-main-color p-3 transition-all duration-500 group-hover:rotate-3 group-hover:scale-110">
                    <img src="{{ asset('storage/' . $imagen) }}" alt="Icon"
                        class="w-13 h-13 object-contain transition-all duration-300 group-hover:brightness-110">
                </div>
        </div>

        <div class="w-full flex-1 py-6 mt-2">
            <h3
                class="text-xl xl:text-[30px] text-gray-800 font-semibold mb-3 transition-all duration-300 group-hover:text-main-color">
                {{ $titulo }}</h3>
            <div class="text-gray-600 text-sm xl:text-[15px] leading-relaxed transition-all duration-300 group-hover:text-gray-700">
                {!! $descripcion !!}
            </div>
        </div>
    </div>
@endif
