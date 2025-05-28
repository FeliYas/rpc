@props(['logos', 'contactos'])
<nav class="w-full z-50" x-data="navbarData">
    <!-- Versión móvil: Logo y menú hamburguesa -->
    <div class="bg-main-color lg:hidden">
        <div class="flex justify-between items-center h-[70px] max-w-[80%] xl:max-w-[1224px] mx-auto">
            <div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/' . $logos[1]->path) }}" alt="logo" class="w-20">
                </a>
            </div>
            <div class="mt-1.5">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class=" focus:outline-none">
                    <i class="fa-solid fa-bars text-xl text-white"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="lg:hidden bg-white shadow-lg overflow-hidden transition-all duration-300 absolute w-full z-40"
        :class="mobileMenuOpen ? 'max-h-screen' : 'max-h-0'" x-cloak>
        <div class="flex flex-col px-4 py-2">
            @if (Route::currentRouteName() == 'zonaprivada' ||
                    Route::currentRouteName() == 'productos.zonaprivada' ||
                    Route::currentRouteName() == 'carrito.zonaprivada' ||
                    Route::currentRouteName() == 'zonaprivada.lista' ||
                    Route::currentRouteName() == 'pedido.confirmacion')
                <a href="{{ route('productos.zonaprivada') }}" class="py-2 border-b border-gray-200">Productos</a>
                <a href="{{ route('carrito.zonaprivada') }}" class="py-2 border-b border-gray-200">Carrito</a>
                <a href="{{ route('zonaprivada') }}" class="py-2 border-b border-gray-200">Lista de precios</a>
                <form method="POST" action="{{ route('cliente.logout') }}">
                    @csrf
                    <button type="submit" class="py-2 border-b border-gray-200 text-red-800">
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            @else
                <a href="{{ route('nosotros') }}" class="py-2 border-b border-gray-200">Nosotros</a>
                <a href="{{ route('categorias') }}" class="py-2 border-b border-gray-200">Productos</a>
                <a href="{{ route('novedades') }}" class="py-2 border-b border-gray-200">Novedades</a>
                <a href="{{ route('contacto') }}" class="py-2 border-b border-gray-200">Contacto</a>
                <a href="#" @click.prevent="showLoginModal = true" class="py-2 border-b border-gray-200">ZONA
                    PRIVADA</a>
            @endif
            <div class="flex items-center py-2">
                <i class="fa-solid fa-envelope mr-2 text-gray-600"></i>
                @foreach ($contactos as $contacto)
                    @if ($contacto->email)
                        <a href="mailto:{{ $contacto->email }}">
                            <p class="text-gray-600">{{ $contacto->email }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @if (Route::currentRouteName() == 'zonaprivada' ||
            Route::currentRouteName() == 'productos.zonaprivada' ||
            Route::currentRouteName() == 'carrito.zonaprivada' ||
            Route::currentRouteName() == 'zonaprivada.lista' ||
            Route::currentRouteName() == 'pedido.confirmacion')
        <div class="hidden lg:block py-4 bg-white border-b border-gray-200">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto flex justify-between items-center relative">
                <div>
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('storage/' . $logos[0]->path) }}" alt="logo">
                    </a>
                </div>
                <div class="flex lg:gap-6 lg:text-[13px] 2xl:text-base relative items-center">
                    @php $currentRoute = Route::currentRouteName(); @endphp
                    <a href="{{ route('productos.zonaprivada') }}"
                        class="relative {{ $currentRoute == 'productos.zonaprivada' ? 'font-bold' : '' }}">
                        Productos
                    </a>
                    <a href="{{ route('carrito.zonaprivada') }}"
                        class="relative {{ $currentRoute == 'carrito.zonaprivada' ? 'font-bold' : '' }}">
                        Carrito
                    </a>
                    <a href="{{ route('zonaprivada') }}"
                        class="relative {{ $currentRoute == 'zonaprivada' ? 'font-bold' : '' }}">
                        Lista de precios
                    </a>
                    <button class="btn-primary-home-largo w-[172px] h-[47px] flex justify-center items-center gap-2"
                        onclick="toggleUserMenu()">
                        <span
                            class="uppercase font-medium">{{ strtoupper(Auth::guard('cliente')->user()->usuario) }}</span>
                    </button>
                    <div id="userSubmenu"
                        class="opacity-0 invisible transform translate-y-28 transition-all duration-300 absolute -right-8 mt-2 bg-white rounded-lg shadow-xl min-w-[220px] overflow-hidden z-[100]">
                        <div class="px-6 py-4 bg-[#0D8141] text-white">
                            <p class="font-medium">{{ Auth::guard('cliente')->user()->usuario }}</p>
                            <p class="text-xs opacity-75 truncate">{{ Auth::guard('cliente')->user()->email }}</p>
                        </div>
                        <div class="py-2">
                            <a href="{{ url('/') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200 transition-colors duration-200">
                                <i class="fa-solid fa-home mr-3 text-[#0D8141]"></i>
                                <span>Ir al inicio</span>
                            </a>
                            <form method="POST" action="{{ route('cliente.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-6 py-3 text-gray-700 hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fa-solid fa-right-from-bracket mr-3 text-[#0D8141]"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="hidden lg:block py-4 bg-white border-b border-gray-200">
            <div class="max-w-[80%] xl:max-w-[1224px] mx-auto flex justify-between items-center relative">
                <div>
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('storage/' . $logos[0]->path) }}" alt="logo">
                    </a>
                </div>
                <div class="flex lg:gap-6 lg:text-[15px] relative items-center">
                    @php $currentRoute = Route::currentRouteName(); @endphp
                    <a href="{{ route('nosotros') }}"
                        class="relative {{ $currentRoute == 'nosotros' ? 'font-bold' : '' }}">
                        Nosotros
                    </a>
                    <a href="{{ route('categorias') }}"
                        class="relative {{ $currentRoute == 'categorias' || $currentRoute == 'productos' || $currentRoute == 'producto' ? 'font-bold' : '' }}">
                        Productos
                    </a>
                    <a href="{{ route('novedades') }}"
                        class="relative {{ $currentRoute == 'novedades' || $currentRoute == 'novedad' ? 'font-bold' : '' }}">
                        Novedades
                    </a>
                    <a href="{{ route('contacto') }}"
                        class="relative {{ $currentRoute == 'contacto' ? 'font-bold' : '' }}">
                        Contacto
                    </a>
                    <a href="#" @click.prevent="showLoginModal = true"
                        class="btn-secondary-home-largo w-[172px] h-[47px] flex items-center text-center justify-center font-medium">ZONA
                        PRIVADA</a>
                </div>
            </div>


        </div>
    @endif
    <!-- Update the login form to use AJAX submission -->
    <div x-show="showLoginModal" x-cloak x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50">
        <!-- Overlay de fondo con opacidad -->
        <div class="absolute inset-0 bg-black opacity-50" @click.self="showLoginModal = false"></div>

        <!-- Contenedor del modal sin opacidad -->
        <div class="relative flex items-center justify-center min-h-screen p-4 ">
            <div class="bg-white p-8 rounded-2xl w-full max-w-md shadow-lg relative "
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.away="showLoginModal = false">
                <!-- Botón de cierre -->
                <button @click="showLoginModal = false"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Contenido -->
                <h2 class="text-xl lg:text-3xl font-bold mb-6 text-center">Iniciar sesión</h2>

                <div x-show="loginError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p x-text="loginError"></p>
                </div>

                <form @submit.prevent="submitLogin" class="flex flex-col items-center lg:block">
                    @csrf
                    <div class="mb-4">
                        <label for="login" class="block text-gray-700 mb-2">Usuario o Email</label>
                        <input type="text" id="login" x-model="loginForm.login"
                            class="w-full px-4 py-2 border border-gray-300" required>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 mb-2">Contraseña</label>
                        <input type="password" id="password" x-model="loginForm.password"
                            class="w-full px-4 py-2 border border-gray-300" required>
                    </div>

                    <button type="submit" class="flex justify-center w-full btn-primary-home-largo">
                        Iniciar sesión
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p>¿No tenés usuario?
                        <a @click.prevent="showLoginModal = false; showRegisterModal = true"
                            class="text-main-color cursor-pointer hover:underline">Regístrate</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal Structure -->
    <div x-show="showRegisterModal" class="fixed inset-0 z-50" x-cloak
        x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Overlay de fondo con opacidad -->
        <div class="absolute inset-0 bg-black opacity-50" @click.self="showRegisterModal = false"></div>

        <!-- Contenedor del modal sin opacidad -->
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white p-8 rounded-2xl w-full max-w-2xl shadow-lg max-h-[90vh] overflow-y-auto relative"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.away="showRegisterModal = false">

                <!-- Botón de cierre en la esquina superior derecha -->
                <button @click="showRegisterModal = false"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h2 class="text-xl lg:text-3xl font-bold mb-6 text-center">Registrarse</h2>

                <form @submit.prevent="submitRegistration">
                    <div class="grid grid-cols-1 lg:grid-cols-2 justify-center items-center lg:items-start gap-4">

                        <div class="mb-4">
                            <label for="reg-usuario" class="block text-gray-700 mb-2">Usuario</label>
                            <input type="text" id="reg-usuario" x-model="registerForm.usuario"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-email" class="block text-gray-700 mb-2">Email</label>
                            <input type="email" id="reg-email" x-model="registerForm.email"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-telefono" class="block text-gray-700 mb-2">Teléfono</label>
                            <input type="number" id="reg-telefono" x-model="registerForm.telefono"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-cuit" class="block text-gray-700 mb-2">CUIT</label>
                            <input type="text" id="reg-cuit" x-model="registerForm.cuit"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-direccion" class="block text-gray-700 mb-2">Dirección</label>
                            <input type="text" id="reg-direccion" x-model="registerForm.direccion"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-direfiscal" class="block text-gray-700 mb-2">Dirección fiscal</label>
                            <input type="text" id="reg-direfiscal" x-model="registerForm.direfiscal"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-provincia" class="block text-gray-700 mb-2">Provincia</label>
                            <input type="text" id="reg-provincia" x-model="registerForm.provincia"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-localidad" class="block text-gray-700 mb-2">Localidad</label>
                            <input type="text" id="reg-localidad" x-model="registerForm.localidad"
                                class="w-full px-4 py-2 border border-gray-300 ">
                        </div>

                        <div class="mb-4">
                            <label for="reg-password" class="block text-gray-700 mb-2">Contraseña</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="reg-password"
                                    x-model="registerForm.password" class="w-full px-4 py-2 border border-gray-300">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-2.5 text-gray-500">
                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reg-password-confirm" class="block text-gray-700 mb-2">Confirmar
                                Contraseña</label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'" id="reg-password-confirm"
                                    x-model="registerForm.password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300">
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-2.5 text-gray-500">
                                    <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showConfirmPassword" xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-home-largo w-full">
                        Registrarse
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p>¿Ya tenés una cuenta?
                        <a @click.prevent="showRegisterModal = false; showLoginModal = true"
                            class="text-main-color cursor-pointer hover:underline">Iniciar sesión</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</nav>
<script>
    function toggleUserMenu() {
        const submenu = document.getElementById('userSubmenu');
        const userIcon = document.getElementById('userIcon');
        const isOpen = submenu.classList.contains('active');

        if (isOpen) {
            // Cerrar menú
            submenu.classList.remove('active');
            submenu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            submenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            userIcon.classList.remove('rotate-180');
        } else {
            // Abrir menú
            submenu.classList.add('active');
            submenu.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            submenu.classList.add('opacity-100', 'visible', 'translate-y-0');
            userIcon.classList.add('rotate-180');
        }
    }

    // Cerrar el menú cuando se hace clic fuera de él
    document.addEventListener('click', function(event) {
        const submenu = document.getElementById('userSubmenu');
        const userIcon = document.getElementById('userIcon');
        const userButton = document.querySelector('button[onclick="toggleUserMenu()"]');

        if (submenu.classList.contains('active') &&
            !submenu.contains(event.target) &&
            event.target !== userButton &&
            !userButton.contains(event.target)) {

            submenu.classList.remove('active');
            submenu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            submenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            userIcon.classList.remove('rotate-180');
        }
    });
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbarData', () => ({
            scrolled: false,
            mobileMenuOpen: false,
            showLoginModal: false,
            showRegisterModal: false,
            showPassword: false,
            showConfirmPassword: false,
            loginError: null,
            loginForm: {
                login: '',
                password: ''
            },
            registerForm: {
                usuario: '',
                email: '',
                telefono: '',
                cuit: '',
                direccion: '',
                direfiscal: '',
                provincia: '',
                localidad: '',
                password: '',
                password_confirmation: ''
            },

            // Add this new method for login submission
            submitLogin() {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                    'content');

                // Create form data
                const formData = new FormData();
                formData.append('login', this.loginForm.login);
                formData.append('password', this.loginForm.password);
                formData.append('_token', csrfToken);

                // Submit using fetch API
                fetch('{{ route('login.zonaprivada') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Login successful - redirect
                            window.location.href = data.redirect;
                        } else {
                            // Login failed - show error
                            this.loginError = data.message;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.loginError = 'Error al iniciar sesión. Intente nuevamente.';
                    });
            },

            submitRegistration() {
                // Your existing registration code
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector(
                    'meta[name="csrf-token"]')?.getAttribute('content');

                axios.post('/cliente/register', this.registerForm)
                    .then(response => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                            this.showRegisterModal = false;
                            this.resetForm();
                        } else {
                            toastr.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        if (error.response && error.response.data && error.response.data
                            .message) {
                            toastr.error(error.response.data.message);
                        } else {
                            toastr.error('Error desconocido al registrar. Intenta más tarde.');
                        }
                    });
            },

            // Método adicional para reiniciar el formulario
            resetForm() {
                this.registerForm = {
                    usuario: '',
                    email: '',
                    telefono: '',
                    cuit: '',
                    direccion: '',
                    direfiscal: '',
                    provincia: '',
                    localidad: '',
                    password: '',
                    password_confirmation: ''
                };
                this.showPassword = false;
                this.showConfirmPassword = false;
            }
        }));
    });
</script>
