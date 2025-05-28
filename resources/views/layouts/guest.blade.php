<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')
    <title>RPC - @yield('title')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <script src="https://kit.fontawesome.com/b68f733bf8.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        #toast-container {
            padding-top: 30px;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen ">
    <x-navbar-home :contactos="$contactos" :logos="$logos" />
    <main class="min-h-[500px] overflow-hidden">
        @yield('content')
    </main>
    <x-footer-home :contactos="$contactos" :logos="$logos" />
    <a href="https://wa.me/+5491165896064" target="_blank"
        class="fixed bottom-18 right-5 lg:right-35 lg:bottom-25 m-4 z-20 bg-green-500 p-3 rounded-full shadow-lg flex items-center justify-center hover:bg-green-600">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M23.3317 19.176C22.9325 18.9774 20.9751 18.02 20.6107 17.8867C20.2463 17.7547 19.981 17.6894 19.7144 18.0867C19.4491 18.4814 18.6868 19.3747 18.4551 19.6387C18.2219 19.904 17.9902 19.936 17.5923 19.7387C17.1943 19.5387 15.9109 19.1214 14.3903 17.772C13.2073 16.7214 12.4074 15.424 12.1756 15.0267C11.9439 14.6307 12.1502 14.416 12.3498 14.2187C12.5293 14.0414 12.7477 13.756 12.9473 13.5254C13.147 13.2934 13.2126 13.128 13.3452 12.8627C13.4792 12.5987 13.4122 12.368 13.3118 12.1694C13.2126 11.9707 12.4168 10.02 12.0845 9.22671C11.7617 8.45471 11.4334 8.56004 11.1896 8.54671C10.9565 8.53604 10.6912 8.53337 10.4259 8.53337C10.1607 8.53337 9.72926 8.63204 9.36485 9.02937C8.9991 9.42537 7.97151 10.384 7.97151 12.3347C7.97151 14.284 9.39701 16.168 9.59663 16.4334C9.79625 16.6974 12.4034 20.7 16.3972 22.416C17.3484 22.824 18.0893 23.068 18.6667 23.2493C19.6206 23.552 20.4888 23.5093 21.1747 23.4067C21.9384 23.2933 23.53 22.448 23.8623 21.5227C24.1932 20.5974 24.1932 19.804 24.0941 19.6387C23.9949 19.4734 23.7296 19.3747 23.3304 19.176H23.3317ZM16.0676 29.0467H16.0623C13.6901 29.0471 11.3616 28.4125 9.32064 27.2093L8.83833 26.924L3.82499 28.2333L5.1634 23.3693L4.84855 22.8707C3.52239 20.7698 2.82057 18.3384 2.82419 15.8574C2.82687 8.59071 8.76732 2.67872 16.073 2.67872C19.6099 2.67872 22.9352 4.05205 25.4352 6.54271C26.6682 7.76482 27.6456 9.21813 28.3106 10.8186C28.9757 12.419 29.3153 14.1348 29.3097 15.8667C29.307 23.1333 23.3666 29.0467 16.0676 29.0467ZM27.3376 4.65071C25.8614 3.17195 24.1051 1.99943 22.1703 1.20112C20.2355 0.402806 18.1608 -0.00543499 16.0663 5.46366e-05C7.28556 5.46366e-05 0.136654 7.11338 0.133975 15.856C0.129906 18.6384 0.863301 21.3725 2.26016 23.7827L0 32L8.44578 29.7947C10.7821 31.0615 13.4003 31.7253 16.0609 31.7253H16.0676C24.8483 31.7253 31.9972 24.612 31.9999 15.868C32.0064 13.7844 31.5977 11.7202 30.7974 9.79475C29.9971 7.86933 28.8212 6.12094 27.3376 4.65071Z"
                fill="white" />
        </svg>
    </a>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
        });
    </script>
</body>

</html>
