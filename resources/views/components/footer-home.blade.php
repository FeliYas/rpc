@props(['logos', 'contactos'])

<footer class="text-white">
    <div class="bg-main-color ">
        <div
            class="2xl:flex grid grid-cols-1 lg:grid-cols-4 gap-10 lg:gap-0 justify-between max-w-[80%] xl:max-w-[1224px] mx-auto py-22">
            <div class="flex justify-center lg:justify-start lg:block 2xl:w-1/4">
                <img src="{{ asset('storage/' . $logos[1]->path) }}" alt="logo">
            </div>
            <div class="2xl:w-1/4">
                <h3 class="font-semibold text-[20px]">Secciones</h3>
                <div
                    class="grid grid-flow-col grid-rows-2 gap-x-10 gap-y-2 mt-7 text-gray-200 lg:text-sm 2xl:text-[15px]">
                    <a href="{{ route('nosotros') }}" class="hover:underline">Nosotros</a>
                    <a href="{{ route('categorias') }}" class="hover:underline">Productos</a>
                    <a href="{{ route('novedades') }}" class="hover:underline">Novedades</a>
                    <a href="{{ route('contacto') }}" class="hover:underline">Contacto</a>
                </div>
            </div>
            <div class="flex flex-col gap-7 2xl:w-1/4">
                <h3 class="font-semibold text-[20px] text-white">Suscribite al Newsletter</h3>
                <form id="newsletterForm" class="relative 2xl:w-[300px]">
                    @csrf
                    <input type="email" name="email" placeholder="Email" required
                        class="bg-transparaten border h-[45px] w-[90%] border-gray-200 px-4 text-sm text-white">
                    <input type="hidden" name="_token">
                    <button type="submit"
                        class="absolute right-7 top-0 h-[45px] w-[45px] flex items-center justify-center  cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
                <div id="newsletterMessage" class="text-sm"></div>
            </div>
            <div class="2xl:w-1/4">
                <h3 class="font-semibold text-[20px] mb-7">Contacto</h3>
                <div class="flex flex-col gap-4 items-start text-start lg:text-start justify-center">
                    @foreach ($contactos as $contacto)
                        @if ($contacto->direccion)
                            <a href="https://maps.google.com/?q={{ urlencode($contacto->direccion) }}" target="_blank"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px]">

                                    <span class="flex items-start gap-2 ">
                                        <svg width="35" height="35" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.6668 8.33341C16.6668 13.3334 10.0002 18.3334 10.0002 18.3334C10.0002 18.3334 3.3335 13.3334 3.3335 8.33341C3.3335 6.5653 4.03588 4.86961 5.28612 3.61937C6.53636 2.36913 8.23205 1.66675 10.0002 1.66675C11.7683 1.66675 13.464 2.36913 14.7142 3.61937C15.9644 4.86961 16.6668 6.5653 16.6668 8.33341Z"
                                                stroke="#FFF" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10 10.8333C11.3807 10.8333 12.5 9.71396 12.5 8.33325C12.5 6.95254 11.3807 5.83325 10 5.83325C8.61929 5.83325 7.5 6.95254 7.5 8.33325C7.5 9.71396 8.61929 10.8333 10 10.8333Z"
                                                stroke="#FFF" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg> {{ $contacto->direccion }}
                                    </span>
                                </p>
                            </a>
                        @endif
                        @if ($contacto->email)
                            <a href="mailto:{{ $contacto->email }}"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px]">
                                    <span class="flex items-center gap-2">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.6665 3.33325H3.33317C2.4127 3.33325 1.6665 4.07944 1.6665 4.99992V14.9999C1.6665 15.9204 2.4127 16.6666 3.33317 16.6666H16.6665C17.587 16.6666 18.3332 15.9204 18.3332 14.9999V4.99992C18.3332 4.07944 17.587 3.33325 16.6665 3.33325Z"
                                                stroke="#FFF" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M18.3332 5.83325L10.8582 10.5833C10.6009 10.7444 10.3034 10.8299 9.99984 10.8299C9.69624 10.8299 9.39878 10.7444 9.1415 10.5833L1.6665 5.83325"
                                                stroke="#FFF" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        {{ $contacto->email }}
                                    </span>
                                </p>
                            </a>
                        @endif
                        @if ($contacto->telefonouno)
                            <a href="tel:{{ preg_replace('/\s+/', '', $contacto->telefonouno) }}"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px]">
                                    <span class="flex items-center gap-2">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 2.91005C16.0831 1.98416 14.991 1.25002 13.7875 0.750416C12.584 0.250812 11.2931 -0.00426317 9.99 5.38951e-05C4.53 5.38951e-05 0.0800002 4.45005 0.0800002 9.91005C0.0800002 11.6601 0.54 13.3601 1.4 14.8601L0 20.0001L5.25 18.6201C6.7 19.4101 8.33 19.8301 9.99 19.8301C15.45 19.8301 19.9 15.3801 19.9 9.92005C19.9 7.27005 18.87 4.78005 17 2.91005ZM9.99 18.1501C8.51 18.1501 7.06 17.7501 5.79 17.0001L5.49 16.8201L2.37 17.6401L3.2 14.6001L3 14.2901C2.17755 12.9771 1.74092 11.4593 1.74 9.91005C1.74 5.37005 5.44 1.67005 9.98 1.67005C12.18 1.67005 14.25 2.53005 15.8 4.09005C16.5676 4.85392 17.1759 5.7626 17.5896 6.76338C18.0033 7.76417 18.2142 8.83714 18.21 9.92005C18.23 14.4601 14.53 18.1501 9.99 18.1501ZM14.51 11.9901C14.26 11.8701 13.04 11.2701 12.82 11.1801C12.59 11.1001 12.43 11.0601 12.26 11.3001C12.09 11.5501 11.62 12.1101 11.48 12.2701C11.34 12.4401 11.19 12.4601 10.94 12.3301C10.69 12.2101 9.89 11.9401 8.95 11.1001C8.21 10.4401 7.72 9.63005 7.57 9.38005C7.43 9.13005 7.55 9.00005 7.68 8.87005C7.79 8.76005 7.93 8.58005 8.05 8.44005C8.17 8.30005 8.22 8.19005 8.3 8.03005C8.38 7.86005 8.34 7.72005 8.28 7.60005C8.22 7.48005 7.72 6.26005 7.52 5.76005C7.32 5.28005 7.11 5.34005 6.96 5.33005H6.48C6.31 5.33005 6.05 5.39005 5.82 5.64005C5.6 5.89005 4.96 6.49005 4.96 7.71005C4.96 8.93005 5.85 10.1101 5.97 10.2701C6.09 10.4401 7.72 12.9401 10.2 14.0101C10.79 14.2701 11.25 14.4201 11.61 14.5301C12.2 14.7201 12.74 14.6901 13.17 14.6301C13.65 14.5601 14.64 14.0301 14.84 13.4501C15.05 12.8701 15.05 12.3801 14.98 12.2701C14.91 12.1601 14.76 12.1101 14.51 11.9901Z"
                                                fill="white" />
                                        </svg>

                                        {{ $contacto->telefonouno }}
                                    </span>
                                </p>
                            </a>
                        @endif
                        @if ($contacto->telefonodos)
                            <a href="tel:{{ preg_replace('/\s+/', '', $contacto->telefonodos) }}"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px]">
                                    <span class="flex items-center gap-2 text-white">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 2.91005C16.0831 1.98416 14.991 1.25002 13.7875 0.750416C12.584 0.250812 11.2931 -0.00426317 9.99 5.38951e-05C4.53 5.38951e-05 0.0800002 4.45005 0.0800002 9.91005C0.0800002 11.6601 0.54 13.3601 1.4 14.8601L0 20.0001L5.25 18.6201C6.7 19.4101 8.33 19.8301 9.99 19.8301C15.45 19.8301 19.9 15.3801 19.9 9.92005C19.9 7.27005 18.87 4.78005 17 2.91005ZM9.99 18.1501C8.51 18.1501 7.06 17.7501 5.79 17.0001L5.49 16.8201L2.37 17.6401L3.2 14.6001L3 14.2901C2.17755 12.9771 1.74092 11.4593 1.74 9.91005C1.74 5.37005 5.44 1.67005 9.98 1.67005C12.18 1.67005 14.25 2.53005 15.8 4.09005C16.5676 4.85392 17.1759 5.7626 17.5896 6.76338C18.0033 7.76417 18.2142 8.83714 18.21 9.92005C18.23 14.4601 14.53 18.1501 9.99 18.1501ZM14.51 11.9901C14.26 11.8701 13.04 11.2701 12.82 11.1801C12.59 11.1001 12.43 11.0601 12.26 11.3001C12.09 11.5501 11.62 12.1101 11.48 12.2701C11.34 12.4401 11.19 12.4601 10.94 12.3301C10.69 12.2101 9.89 11.9401 8.95 11.1001C8.21 10.4401 7.72 9.63005 7.57 9.38005C7.43 9.13005 7.55 9.00005 7.68 8.87005C7.79 8.76005 7.93 8.58005 8.05 8.44005C8.17 8.30005 8.22 8.19005 8.3 8.03005C8.38 7.86005 8.34 7.72005 8.28 7.60005C8.22 7.48005 7.72 6.26005 7.52 5.76005C7.32 5.28005 7.11 5.34005 6.96 5.33005H6.48C6.31 5.33005 6.05 5.39005 5.82 5.64005C5.6 5.89005 4.96 6.49005 4.96 7.71005C4.96 8.93005 5.85 10.1101 5.97 10.2701C6.09 10.4401 7.72 12.9401 10.2 14.0101C10.79 14.2701 11.25 14.4201 11.61 14.5301C12.2 14.7201 12.74 14.6901 13.17 14.6301C13.65 14.5601 14.64 14.0301 14.84 13.4501C15.05 12.8701 15.05 12.3801 14.98 12.2701C14.91 12.1601 14.76 12.1101 14.51 11.9901Z"
                                                fill="white" />
                                        </svg>
                                        {{ $contacto->telefonodos }}
                                    </span>
                                </p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div style="background-color: #178649">
        <div
            class="flex justify-between items-center max-w-[80%] xl:max-w-[1224px] mx-auto py-6 text-xs lg:text-base text-gray-300">
            <p>© Copyright 2025 <span class="font-semibold">Industria R.P.C.</span> Todos los derechos reservados</p>
            <p class="font-light">By
                <a href="https://osole.com.ar/#" class="font-normal hover:underline hover:text-blue-600">
                    Osole
                </a>
            </p>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('newsletterForm');
            const messageDiv = document.getElementById('newsletterMessage');

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    axios.post('{{ route('newsletter.store') }}', formData, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => {
                            const data = response.data;

                            if (data.success) {
                                messageDiv.innerHTML = '<span class="text-green-500">' + data.message +
                                    '</span>';
                                form.reset();
                            } else {
                                messageDiv.innerHTML = '<span class="text-red-500">' + data.message +
                                    '</span>';
                            }

                            setTimeout(() => {
                                messageDiv.innerHTML = '';
                            }, 3000);
                        })
                        .catch(error => {
                            console.error(error);
                            messageDiv.innerHTML =
                                '<span class="text-red-500">Error al procesar la solicitud</span>';
                        });
                });
            }
        });
    </script>

</footer>
