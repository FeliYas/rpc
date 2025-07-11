@props(['novedad', 'deleteRoute', 'updateRoute'])

@if (isset($deleteRoute) || isset($updateRoute))
    <div class="bg-gray-50 h-[493px] p-4 rounded-md">
        <img src="{{ asset('storage/' . $novedad->path) }}" alt="{{ $novedad->titulo }}"
            class="object-cover w-full h-[246px]">
        <div class="mt-6">
            <div class="flex text-center items-start gap-4">
                <h3 class="text-2xl font-medium">{{ ucfirst($novedad->titulo) }}</h3>
                <p class="text-gray-500 mt-1.5">{{ strtoupper($novedad->orden) }}</p>
            </div>

            <div class="text-gray-500 line-clamp-3 h-[96px] py-6">{!! $novedad->descripcion !!}</div>

            <div class="flex gap-6 mt-4">
                <!-- Cambiamos el evento onclick para usar un atributo data-id -->
                <button type="button" data-id="{{ $novedad->id }}"
                    class="edit-novedad-btn hover:bg-orange-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button type="button" data-id="{{ $novedad->id }}"
                    class="delete-novedad-btn text-red-600 hover:bg-red-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@else
    <div class="bg-gray-50 xl:h-[493px] p-4 rounded-md border border-gray-200">
        <img src="{{ asset('storage/' . $novedad->path) }}" alt="{{ $novedad->titulo }}"
            class="object-cover w-full h-[246px]">
        <div class="mt-6">
            <div>
                <h3 class="text-2xl font-medium">{{ ucfirst($novedad->titulo) }}</h3>
            </div>

            <div class="text-gray-500 line-clamp-4 py-5">{!! $novedad->descripcion !!}</div>

            <div class="flex text-black">
                <a href="{{ route('novedad', ['id' => $novedad->id]) }}" class="hover:underline text-black font-medium">LEER MÁS</a>
            </div>
        </div>
    </div>
@endif
