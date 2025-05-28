<div class="bg-gray-100 shadow-md flex justify-between items-center z-50 transition-all duration-300 hover:shadow-xl rounded-2xl mt-4 mx-8"
    style="height: 64px; padding: 0 2rem; border-bottom: 1px solid rgba(13, 129, 65, 0.1);">
    <div class="flex items-center">
        <div class="w-1.5 h-6 bg-[#0D8141] rounded-sm mr-3"></div>
        <h1 class="text-lg" style="color: #0D8141;">Panel de Administración</h1>
    </div>
    <div class="relative">
        <button class="flex items-center gap-2 py-2 px-3 rounded-full hover:bg-gray-100 transition-all duration-200"
            style="color:#0D8141" onclick="toggleUserMenu()">
            <span class="hidden sm:block text-gray-700 font-medium">{{ Auth::user()->name }}</span>
            <i class="fa-sharp fa-solid fa-circle-user fa-lg transition-transform duration-300" id="userIcon"></i>
        </button>
        <div id="userSubmenu"
            class="opacity-0 invisible transform translate-y-2 transition-all duration-300 absolute right-0 mt-2 bg-white rounded-lg shadow-xl min-w-[220px] overflow-hidden z-[100]">
            <div class="px-6 py-4 bg-[#0D8141] text-white">
                <p class="font-medium">{{ Auth::user()->name }}</p>
                <p class="text-xs opacity-75 truncate">{{ Auth::user()->email }}</p>
            </div>
            <div class="py-2">
                <a href="{{ url('/') }}"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-200 transition-colors duration-200">
                    <i class="fa-solid fa-home mr-3 text-[#0D8141]"></i>
                    <span>Ir al inicio</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
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
</script>
