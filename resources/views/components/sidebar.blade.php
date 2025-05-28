@props(['logo'])

<!-- Sidebar -->
<div
    class="p-3 flex flex-col bg-main-color h-full fixed w-[250px] overflow-auto transition-all duration-300 shadow-lg z-9">
    <!-- Logo Area -->
    <div class="flex-grow">
        <div
            class="flex items-center justify-center text-center mb-auto p-2 hover:scale-105 transition-transform duration-300">
            <a href="{{ route('dashboard') }}" class="inline-block relative group">
                <img src="{{ asset('storage/' . $logo->path) }}" alt="Logo" class="object-cover relative z-10">
            </a>
        </div>
        <hr class="mx-3 mb-5 mt-5 border-t-[3px] border-white opacity-70 rounded">

        <!-- Secciones -->
        <div>
            <ul class="list-unstyled flex flex-col gap-1.5">
                <li>
                    <button
                        class="w-full text-left text-white grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-all duration-200 group"
                        onclick="toggleSubMenuHome('homeSubmenu')">
                        <i class="fa-solid fa-house text-center w-5 text-white group-hover:text-white"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">Home</span>
                        <svg class="ml-auto w-4 h-4 transition-transform duration-300" id="homeSubmenuIcon"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul id="homeSubmenu" class="relative overflow-hidden transition-all duration-300 max-h-0 mt-1">

                        <div class="absolute left-6 w-0.5 h-25 bg-gray-200 opacity-80 rounded-sm mr-3"></div>
                        <li>
                            <a href="{{ route('slider.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-image-portrait w-5 mr-2 text-xs"></i>
                                    Slider
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contenido.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-align-left w-5 mr-2 text-xs"></i>
                                    Contenido
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('nosotros.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group">
                        <i class="fa-solid fa-address-card text-center w-5 text-white"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">Nosotros</span>
                        <span></span>
                    </a>
                </li>
                <li>
                    <button
                        class="w-full text-left text-white grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-all duration-200 group"
                        onclick="toggleSubMenuHome('productosSubmenu')">
                        <i class="fa-solid fa-cart-shopping text-center w-5 text-white group-hover:text-white"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">Productos</span>
                        <svg class="ml-auto w-4 h-4 transition-transform duration-300" id="productosSubmenuIcon"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul id="productosSubmenu" class="relative overflow-hidden transition-all duration-300 max-h-0 mt-1">
                        <div class="absolute left-6 w-0.5 h-20 bg-gray-200 opacity-80 rounded-sm mr-3"></div>

                        <li>
                            <a href="{{ route('categorias.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-layer-group w-5 mr-2 text-xs"></i>
                                    Categorías
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('productos.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-box-open w-5 mr-2 text-xs"></i>
                                    Productos
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('novedades.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group">
                        <i class="fa-solid fa-newspaper text-center w-5 text-white"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">Novedades</span>
                        <span></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('contacto.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group">
                        <i class="fa-solid fa-square-phone text-center w-5 text-white"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">Contacto</span>
                        <span></span>
                    </a>
                </li>
                <li>
                    <button
                        class="w-full text-left text-white grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-all duration-200 group"
                        onclick="toggleSubMenuHome('zonaSubmenu')">
                        <i class="fa-solid fa-lock text-center w-5 text-white group-hover:text-white"></i>
                        <span class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">Zona
                            Privada</span>
                        <svg class="ml-auto w-4 h-4 transition-transform duration-300" id="zonaSubmenuIcon"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul id="zonaSubmenu" class="relative overflow-hidden transition-all duration-300 max-h-0 mt-1">
                        <div class="absolute left-6 w-0.5 h-20 bg-gray-200 opacity-80 rounded-sm mr-3"></div>

                        <li>
                            <a href="{{ route('clientes.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-users w-5 mr-2 text-xs"></i>
                                    Clientes
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('carrito.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-cart-shopping w-5 mr-2 text-xs"></i>
                                    Carrito
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('precios.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-tag w-5 mr-2 text-xs"></i>
                                    Lista de precios
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('descuentos.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-percent w-5 mr-2 text-xs"></i>
                                    Descuentos
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pedidos.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-clipboard-list w-5 mr-2 text-xs"></i>
                                    Pedidos
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr class="mx-6 my-2 border-t-[1px] border-white/30">
                <li>
                    <button
                        class="w-full text-left text-white grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-all duration-200 group"
                        onclick="toggleSubMenuHome('ivaSubmenu')">
                        <i class="fa-solid fa-calculator text-center w-5 text-white relative z-10"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200">IVA</span>
                        <svg class="ml-auto w-4 h-4 transition-transform duration-300" id="ivaSubmenuIcon"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul id="ivaSubmenu" class="relative overflow-hidden transition-all duration-300 max-h-0 mt-1">

                        <div class="absolute left-6 w-0.5 h-25 bg-gray-200 opacity-80 rounded-sm mr-3"></div>
                        <li>
                            <a href="{{ route('proveedores.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-truck w-5 mr-2 text-xs"></i>
                                    Proveedores
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('facturas.dashboard') }}"
                                class="block pl-10 py-2 text-gray-200 hover:text-white rounded-lg transition-all duration-200 my-1">
                                <span class="opacity-80 hover:opacity-100 transition-opacity flex items-center">
                                    <i class="fa-solid fa-receipt w-5 mr-2 text-xs"></i>
                                    Facturas
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li>
                    <a href="{{ route('iva.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group relative overflow-hidden">
                        <div
                            class="absolute left-0 top-0 h-full w-0 bg-white/10 transition-all duration-300 group-hover:w-full rounded-lg">
                        </div>
                        
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200 relative z-10">IVA</span>
                        <span class="relative z-10"></span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('logos.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group relative overflow-hidden">
                        <div
                            class="absolute left-0 top-0 h-full w-0 bg-white/10 transition-all duration-300 group-hover:w-full rounded-lg">
                        </div>
                        <i class="fa-solid fa-image text-center w-5 text-white relative z-10"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200 relative z-10">Logos</span>
                        <span class="relative z-10"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('newsletter.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group relative overflow-hidden">
                        <div
                            class="absolute left-0 top-0 h-full w-0 bg-white/10 transition-all duration-300 group-hover:w-full rounded-lg">
                        </div>
                        <i class="fa-solid fa-envelopes-bulk text-center w-5 text-white relative z-10"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200 relative z-10">Newsletter</span>
                        <span class="relative z-10"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('usuarios.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group relative overflow-hidden">
                        <div
                            class="absolute left-0 top-0 h-full w-0 bg-white/10 transition-all duration-300 group-hover:w-full rounded-lg">
                        </div>
                        <i class="fa-solid fa-users text-center w-5 text-white relative z-10"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200 relative z-10">Usuarios</span>
                        <span class="relative z-10"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('metadatos.dashboard') }}"
                        class="grid grid-cols-[24px_1fr_24px] items-center gap-3 px-4 py-3 text-white hover:bg-white/10 rounded-lg transition-all duration-200 group relative overflow-hidden">
                        <div
                            class="absolute left-0 top-0 h-full w-0 bg-white/10 transition-all duration-300 group-hover:w-full rounded-lg">
                        </div>
                        <i class="fa-solid fa-magnifying-glass text-center w-5 text-white relative z-10"></i>
                        <span
                            class="whitespace-nowrap group-hover:translate-x-1 transition-transform duration-200 relative z-10">Metadatos</span>
                        <span class="relative z-10"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Footer del sidebar -->
    <div class="mt-auto">
        <hr class="mx-3 my-3 border-t-[3px] border-white opacity-70 rounded">
        <p class="text-gray-300 text-xs text-center px-3 py-2">
            <span class="opacity-70 hover:opacity-100 transition-opacity duration-200">@2025 RPC - by Osole</span>
        </p>
    </div>
</div>

<script>
    function toggleSubMenuHome(submenuId) {
        const submenu = document.getElementById(submenuId);
        const icon = document.getElementById(submenuId + 'Icon');

        // Si el menú está cerrado
        if (submenu.style.maxHeight === '' || submenu.style.maxHeight === '0px') {
            // Obtenemos la altura real del contenido
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)';
        } else {
            // Cerramos el menú
            submenu.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Efecto de entrada al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.bg-main-color');
        const menuItems = document.querySelectorAll('a, button');

        // Primero hacemos que el sidebar entre con una animación
        sidebar.classList.add('animate-sidebar-in');

        // Luego animamos cada elemento del menú con un pequeño retraso
        menuItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('animate-fade-in');
            }, 100 + (index * 50));
        });
    });
</script>

<style>
    @keyframes sidebarIn {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-sidebar-in {
        animation: sidebarIn 0.4s ease-out forwards;
    }

    .animate-fade-in {
        opacity: 0;
        animation: fadeIn 0.3s ease-out forwards;
    }

    /* Personaliza la barra de desplazamiento */
    .overflow-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-auto::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
