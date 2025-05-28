@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection
@section('title', 'Contacto')

@section('content')
    <div class="h-[180px] lg:h-[250px] bg-gray-100">
        <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
            <!-- Ruta de navegación -->
            <div class="text-black hidden lg:block py-4">
                <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a href="{{ route('contacto') }}" class="font-light hover:underline">Contacto</a>
                <span class="mx-[5px]"></span>
            </div>
            <div class="flex justify-center items-center h-full lg:mt-8">
                <h1 class="text-2xl lg:text-[42px] font-bold text-center mt-20 lg:mt-0">Contacto</h1>

            </div>
        </div>
    </div>
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">

        <div class="flex flex-col lg:flex-row justify-between gap-12 py-5 lg:py-18">
            <div class="lg:w-1/2 flex flex-col gap-2 lg:gap-4">
                <div class="flex flex-col text-center lg:text-left gap-2 mb-3 max-w-[550px]">
                    <h2 class="titulo-home">Déjanos un mensaje</h2>
                    <p style="color: #5C5C5C">Para mayor información, no dude en contactarse mediante el siguiente
                        formulario, o a través de
                        nuestras vías de comunicación.</p>
                </div>
                @foreach ($contactos as $contacto)
                    @if ($contacto->direccion)
                        <a href="https://maps.google.com/?q={{ urlencode($contacto->direccion) }}" target="_blank"
                            class="block no-underline text-inherit hover:text-main-color">
                            <p class="lg:text-sm 2xl:text-[15px] mt-4.5" style="color: #5C5C5C">
                                <span class="flex items-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.6668 8.33341C16.6668 13.3334 10.0002 18.3334 10.0002 18.3334C10.0002 18.3334 3.3335 13.3334 3.3335 8.33341C3.3335 6.5653 4.03588 4.86961 5.28612 3.61937C6.53636 2.36913 8.23205 1.66675 10.0002 1.66675C11.7683 1.66675 13.464 2.36913 14.7142 3.61937C15.9644 4.86961 16.6668 6.5653 16.6668 8.33341Z"
                                            stroke="#0D8141" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M10 10.8333C11.3807 10.8333 12.5 9.71396 12.5 8.33325C12.5 6.95254 11.3807 5.83325 10 5.83325C8.61929 5.83325 7.5 6.95254 7.5 8.33325C7.5 9.71396 8.61929 10.8333 10 10.8333Z"
                                            stroke="#0D8141" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg> {{ $contacto->direccion }}
                                </span>
                            </p>
                        </a>
                    @endif
                    @if ($contacto->email)
                        <a href="mailto:{{ $contacto->email }}"
                            class="block no-underline text-inherit hover:text-main-color">
                            <p class="lg:text-sm 2xl:text-[15px] mt-4" style="color: #5C5C5C">
                                <span class="flex items-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.6665 3.33325H3.33317C2.4127 3.33325 1.6665 4.07944 1.6665 4.99992V14.9999C1.6665 15.9204 2.4127 16.6666 3.33317 16.6666H16.6665C17.587 16.6666 18.3332 15.9204 18.3332 14.9999V4.99992C18.3332 4.07944 17.587 3.33325 16.6665 3.33325Z"
                                            stroke="#0D8141" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.3332 5.83325L10.8582 10.5833C10.6009 10.7444 10.3034 10.8299 9.99984 10.8299C9.69624 10.8299 9.39878 10.7444 9.1415 10.5833L1.6665 5.83325"
                                            stroke="#0D8141" stroke-width="2" stroke-linecap="round"
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
                            <p class="lg:text-sm 2xl:text-[15px] mt-4" style="color: #5C5C5C">
                                <span class="flex items-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17 2.91005C16.0831 1.98416 14.991 1.25002 13.7875 0.750416C12.584 0.250812 11.2931 -0.00426317 9.99 5.38951e-05C4.53 5.38951e-05 0.0800002 4.45005 0.0800002 9.91005C0.0800002 11.6601 0.54 13.3601 1.4 14.8601L0 20.0001L5.25 18.6201C6.7 19.4101 8.33 19.8301 9.99 19.8301C15.45 19.8301 19.9 15.3801 19.9 9.92005C19.9 7.27005 18.87 4.78005 17 2.91005ZM9.99 18.1501C8.51 18.1501 7.06 17.7501 5.79 17.0001L5.49 16.8201L2.37 17.6401L3.2 14.6001L3 14.2901C2.17755 12.9771 1.74092 11.4593 1.74 9.91005C1.74 5.37005 5.44 1.67005 9.98 1.67005C12.18 1.67005 14.25 2.53005 15.8 4.09005C16.5676 4.85392 17.1759 5.7626 17.5896 6.76338C18.0033 7.76417 18.2142 8.83714 18.21 9.92005C18.23 14.4601 14.53 18.1501 9.99 18.1501ZM14.51 11.9901C14.26 11.8701 13.04 11.2701 12.82 11.1801C12.59 11.1001 12.43 11.0601 12.26 11.3001C12.09 11.5501 11.62 12.1101 11.48 12.2701C11.34 12.4401 11.19 12.4601 10.94 12.3301C10.69 12.2101 9.89 11.9401 8.95 11.1001C8.21 10.4401 7.72 9.63005 7.57 9.38005C7.43 9.13005 7.55 9.00005 7.68 8.87005C7.79 8.76005 7.93 8.58005 8.05 8.44005C8.17 8.30005 8.22 8.19005 8.3 8.03005C8.38 7.86005 8.34 7.72005 8.28 7.60005C8.22 7.48005 7.72 6.26005 7.52 5.76005C7.32 5.28005 7.11 5.34005 6.96 5.33005H6.48C6.31 5.33005 6.05 5.39005 5.82 5.64005C5.6 5.89005 4.96 6.49005 4.96 7.71005C4.96 8.93005 5.85 10.1101 5.97 10.2701C6.09 10.4401 7.72 12.9401 10.2 14.0101C10.79 14.2701 11.25 14.4201 11.61 14.5301C12.2 14.7201 12.74 14.6901 13.17 14.6301C13.65 14.5601 14.64 14.0301 14.84 13.4501C15.05 12.8701 15.05 12.3801 14.98 12.2701C14.91 12.1601 14.76 12.1101 14.51 11.9901Z"
                                            fill="#0D8141" />
                                    </svg>
                                    {{ $contacto->telefonouno }}
                                </span>
                            </p>
                        </a>
                    @endif
                    @if ($contacto->telefonodos)
                        <a href="tel:{{ preg_replace('/\s+/', '', $contacto->telefonodos) }}"
                            class="block no-underline text-inherit hover:text-main-color">
                            <p class="lg:text-sm 2xl:text-[15px] mt-4" style="color: #5C5C5C">
                                <span class="flex items-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17 2.91005C16.0831 1.98416 14.991 1.25002 13.7875 0.750416C12.584 0.250812 11.2931 -0.00426317 9.99 5.38951e-05C4.53 5.38951e-05 0.0800002 4.45005 0.0800002 9.91005C0.0800002 11.6601 0.54 13.3601 1.4 14.8601L0 20.0001L5.25 18.6201C6.7 19.4101 8.33 19.8301 9.99 19.8301C15.45 19.8301 19.9 15.3801 19.9 9.92005C19.9 7.27005 18.87 4.78005 17 2.91005ZM9.99 18.1501C8.51 18.1501 7.06 17.7501 5.79 17.0001L5.49 16.8201L2.37 17.6401L3.2 14.6001L3 14.2901C2.17755 12.9771 1.74092 11.4593 1.74 9.91005C1.74 5.37005 5.44 1.67005 9.98 1.67005C12.18 1.67005 14.25 2.53005 15.8 4.09005C16.5676 4.85392 17.1759 5.7626 17.5896 6.76338C18.0033 7.76417 18.2142 8.83714 18.21 9.92005C18.23 14.4601 14.53 18.1501 9.99 18.1501ZM14.51 11.9901C14.26 11.8701 13.04 11.2701 12.82 11.1801C12.59 11.1001 12.43 11.0601 12.26 11.3001C12.09 11.5501 11.62 12.1101 11.48 12.2701C11.34 12.4401 11.19 12.4601 10.94 12.3301C10.69 12.2101 9.89 11.9401 8.95 11.1001C8.21 10.4401 7.72 9.63005 7.57 9.38005C7.43 9.13005 7.55 9.00005 7.68 8.87005C7.79 8.76005 7.93 8.58005 8.05 8.44005C8.17 8.30005 8.22 8.19005 8.3 8.03005C8.38 7.86005 8.34 7.72005 8.28 7.60005C8.22 7.48005 7.72 6.26005 7.52 5.76005C7.32 5.28005 7.11 5.34005 6.96 5.33005H6.48C6.31 5.33005 6.05 5.39005 5.82 5.64005C5.6 5.89005 4.96 6.49005 4.96 7.71005C4.96 8.93005 5.85 10.1101 5.97 10.2701C6.09 10.4401 7.72 12.9401 10.2 14.0101C10.79 14.2701 11.25 14.4201 11.61 14.5301C12.2 14.7201 12.74 14.6901 13.17 14.6301C13.65 14.5601 14.64 14.0301 14.84 13.4501C15.05 12.8701 15.05 12.3801 14.98 12.2701C14.91 12.1601 14.76 12.1101 14.51 11.9901Z"
                                            fill="#0D8141" />
                                    </svg>
                                    {{ $contacto->telefonodos }}
                                </span>
                            </p>
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="lg:w-1/2 mt-17">
                <form action="{{ route('contacto.enviar') }}" method="POST" class="w-full space-y-6 text-[#5C5C5C]"
                    id="contactForm">
                    @csrf
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="w-full">
                            <label for="nombre" class="block mb-1">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                class="border border-gray-300 w-full h-12 px-4">
                        </div>
                        <div class="w-full">
                            <label for="apellido" class="block mb-1">Apellido</label>
                            <input type="text" name="apellido" id="apellido"
                                class="border border-gray-300 w-full h-12 px-4">
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="w-full">
                            <label for="telefono" class="block mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono"
                                class="border border-gray-300 w-full h-12 px-4">
                        </div>
                        <div class="w-full">
                            <label for="empresa" class="block mb-1">Empresa</label>
                            <input type="text" name="empresa" id="empresa"
                                class="border border-gray-300 w-full h-12 px-4">
                        </div>
                    </div>
                    <div class="w-full py-2">
                        <label for="mensaje" class="block mb-1">Mensaje</label>
                        <textarea name="mensaje" id="mensaje" cols="30" rows="10" class="border border-gray-300 w-full px-4"></textarea>
                    </div>
                    <div class="w-full">
                        <div class="mt-auto py-1 flex justify-center ">

                            <!-- Agregamos campo oculto para almacenar el token de reCAPTCHA -->
                            <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">

                            <button type="button" id="submitBtn" class="btn-primary-home-largo w-full font-medium">
                                ENVIAR MENSAJE
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Script de reCAPTCHA v3 -->
                <script src="https://www.google.com/recaptcha/api.js?render=6LfunycrAAAAAAUdd5QxBm7AeK_9ec2Phizdo6LA
                                                                                                "></script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Agregar evento al botón de envío
                        document.getElementById('submitBtn').addEventListener('click', handleSubmit);

                        function handleSubmit(e) {
                            e.preventDefault();

                            // Activar reCAPTCHA
                            grecaptcha.ready(function() {
                                grecaptcha.execute('6LfunycrAAAAAAUdd5QxBm7AeK_9ec2Phizdo6LA', {
                                    action: 'submit_contact'
                                }).then(function(token) {
                                    // Guardar el token en el campo oculto
                                    document.getElementById('recaptchaResponse').value = token;

                                    // Enviar el formulario
                                    document.getElementById('contactForm').submit();
                                });
                            });
                        }
                    });
                </script>
            </div>
        </div>
        <div class="mb-20">
            <div class="w-full h-[600px]">
                {!! preg_replace(['/width="[^"]*"/', '/height="[^"]*"/'], ['width="100%"', 'height="100%"'], $mapa) !!}
            </div>
        </div>
    </div>
@endsection
