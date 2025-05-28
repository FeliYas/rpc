<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/b68f733bf8.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos adicionales para modernizar manteniendo la estructura */
        .login-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .back-link {
            transition: transform 0.2s ease;
        }

        .back-link:hover {
            transform: translateX(-3px);
        }

        .input-focus-effect:focus {
            transform: scale(1.01);
        }

        /* Agregar borde verde más visible en focus */
        .input-focus-effect:focus {
            border-width: 2px;
        }

        /* Estilo para mensajes de error */
        .error-animation {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-8px);
            }

            75% {
                transform: translateX(8px);
            }
        }

        /* Mejora para el spinner */
        .spinner-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Borde flotante para inputs */
        .form-input-container {
            position: relative;
        }

        /* Efecto para el botón de recordar */
        .remember-checkbox {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .remember-checkbox:checked {
            accent-color: #0D8141;
        }

        /* Mejora en la animación de las partículas */
        #particles-js {
            background-image: linear-gradient(to bottom, rgba(247, 250, 252, 0.8), rgba(247, 250, 252, 0.9));
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div id="particles-js" class="fixed inset-0 z-0"></div>
    <div class="z-10">
        <a href="{{ route('home') }}"
            class="back-link flex absolute top-4 left-4 text-main-color px-4 py-2 outline-none hover:underline">
            <i class="fa-solid fa-arrow-left mr-2 p-1" style="color: #0D8141;"></i>Volver al Home
        </a>
        <div>
            <div class="flex justify-center p-6 mb-6">
                <img src="{{ asset('storage/' . $logo->path) }}" alt="{{ $logo->seccion }}"
                    class="h-20 transition-transform duration-300 hover:scale-105">
            </div>
            <div class="bg-white shadow-2xl shadow-[#292b2a] rounded-lg p-8 max-w-lg w-[500px] login-card">
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    <div>
                        <h2 class="mb-2 text-lg font-medium text-gray-800">Ingresar</h2>
                        <hr class="mb-5 border-t-[2px] border-main-color w-16 transition-all duration-300">
                    </div>

                    <!-- Mensaje de error mejorado -->
                    <div id="errorContainer"
                        class="hidden mb-4 p-4 rounded-md bg-red-50 border-l-4 border-red-400 text-red-600 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium" id="errorMessage">Credenciales incorrectas</p>
                        </div>
                    </div>

                    <div class="mb-5 form-input-container">
                        <label class="block text-gray-700 mb-2 font-medium">Usuario o Correo electrónico</label>
                        <input type="text" name="login" id="login"
                            class="input-focus-effect rounded-md w-full px-4 py-3 border border-main-color focus:border-main-color focus:ring focus:ring-main-color/25 focus:ring-opacity-50 outline-none"
                            required>
                    </div>

                    <div class="mb-5 form-input-container">
                        <label class="block text-gray-700 mb-2 font-medium">Contraseña</label>
                        <input type="password" name="password" id="password"
                            class="input-focus-effect rounded-md w-full px-4 py-3 border border-main-color focus:border-main-color focus:ring focus:ring-main-color/25 focus:ring-opacity-50 outline-none"
                            required>
                    </div>

                    <div class="flex items-center mb-6">
                        <input type="checkbox" name="remember" id="remember" class="remember-checkbox mr-2 w-4 h-4">
                        <label for="remember" class="text-gray-700 select-none cursor-pointer">Recuérdame</label>
                    </div>

                    <hr class="mb-6 border-t-[2px] border-main-color opacity-50">

                    <button type="submit" id="loginButton"
                        class="btn-secondary w-full flex items-center justify-center py-3 font-medium">
                        <span>Iniciar Sesión</span>
                    </button>

                    <!-- Botón de carga (inicialmente oculto) -->
                    <button type="button" id="loadingButton"
                        class="btn-secondary w-full hidden items-center justify-center py-3" disabled>
                        <span class="spinner-container">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-main-color"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>Verificando...</span>
                        </span>
                    </button>
                </form>
            </div>
            <div class="flex justify-between text-gray-500 text-sm mt-6 font-semibold">
                <span>© 2025 RPC</span>
                <span>by Osole</span>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración de particles.js (mantén tu configuración actual con pequeñas mejoras)
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 100, // Reducido para un aspecto más minimalista
                        "density": {
                            "enable": true,
                            "value_area": 900
                        }
                    },
                    "color": {
                        "value": "#0D8141"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.4, // Reducido para más sutileza
                        "random": true, // Variación para un aspecto más moderno
                        "anim": {
                            "enable": true,
                            "speed": 0.5,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": true,
                            "speed": 2,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 160,
                        "color": "#0D8141",
                        "opacity": 0.3, // Más sutil
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 1.2, // Más lento para apariencia elegante
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": true, // Activado para movimiento más interesante
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 0.8 // Más visible en interacción
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });

            // Formulario de login con indicador de carga
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loadingButton = document.getElementById('loadingButton');
            const errorContainer = document.getElementById('errorContainer');
            const errorMessage = document.getElementById('errorMessage');
            const loginCard = document.querySelector('.login-card');

            // Si hay un error de sesión al cargar, mostrar el contenedor de error personalizado
            @if (session('loginError'))
                errorMessage.textContent = "{{ session('loginError') }}";
                errorContainer.classList.remove('hidden');
                loginCard.classList.add('error-animation');
                setTimeout(() => {
                    loginCard.classList.remove('error-animation');
                }, 1000);
            @endif

            // Cuando se envía el formulario, mostrar indicador de carga
            loginForm.addEventListener('submit', function(e) {
                // Ocultar botón normal y mostrar botón de carga
                loginButton.classList.add('hidden');
                loadingButton.classList.remove('hidden');
                loadingButton.classList.add('flex');

                // Si hay errores visibles, ocultarlos
                errorContainer.classList.add('hidden');
            });

            // Efecto hover para la línea debajo del título
            const titleContainer = document.querySelector('h2').parentElement;
            const divider = titleContainer.querySelector('hr');

            titleContainer.addEventListener('mouseover', () => {
                divider.classList.add('w-full');
                divider.classList.remove('w-16');
            });

            titleContainer.addEventListener('mouseout', () => {
                divider.classList.remove('w-full');
                divider.classList.add('w-16');
            });
        });
    </script>
</body>

</html>
