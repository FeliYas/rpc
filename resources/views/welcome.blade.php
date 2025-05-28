@extends('layouts.guest')
@section('title', 'Home')

@section('content')
    <div>
        <div class="relative overflow-hidden">
            <!-- Slider Track -->
            <div class="slider-track flex transition-transform duration-500 ease-in-out">
                @foreach ($sliders as $slider)
                    <div class="slider-item min-w-full relative h-[600px] lg:h-[668px]">
                        <div class="absolute inset-0 bg-black z-0">
                            @php
                                $extension = pathinfo($slider->path, PATHINFO_EXTENSION);
                            @endphp
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ asset('storage/' . $slider->path) }}" alt="Slider Image"
                                    class="w-full h-full object-cover" data-duration="6000">
                            @elseif (in_array($extension, ['mp4', 'webm', 'ogg']))
                                <video class="w-full h-full object-cover" autoplay muted onended="nextSlide()">
                                    <source src="{{ asset('storage/' . $slider->path) }}" type="video/{{ $extension }}">
                                    Tu navegador no soporta el formato de video.
                                </video>
                            @endif
                        </div>

                        <div class="absolute bg-black opacity-30 z-50"></div>
                        <div class="absolute inset-0 flex items-center z-20 max-w-[80%] xl:max-w-[1224px] mx-auto">
                            <div class="flex text-center items-center flex-col gap-6 lg:gap-19 w-full">
                                <div class="text-white">
                                    <div class="flex flex-col gap-3.5">
                                        <h1 class="max-w-[700px] text-2xl lg:text-[52px] leading-10 font-semibold">
                                            {{ $slider->titulo }}
                                        </h1>
                                        <div class="custom-summernote text-xl">
                                            <p>
                                                {!! $slider->descripcion !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('categorias') }}" class="btn-primary-home">VER PRODUCTOS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="absolute bottom-20 w-full flex justify-center z-30">
                <div class="flex space-x-1 lg:space-x-2">
                    @foreach ($sliders as $index => $slider)
                        <button
                            class="cursor-pointer dot w-6 lg:w-12 h-1.5 rounded-none transition-colors duration-300 bg-white {{ $index === 0 ? 'opacity-90' : 'opacity-50' }}"
                            onclick="goToSlide({{ $index }})">
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Main elements
                const sliderTrack = document.querySelector('.slider-track');
                const sliderItems = document.querySelectorAll('.slider-item');
                const dots = document.querySelectorAll('.dot');

                let currentIndex = 0;
                let autoSlideTimeout;
                let isTransitioning = false;

                // Global function for next slide (needed for video onended attribute)
                window.nextSlide = function() {
                    if (isTransitioning) return;
                    clearTimeout(autoSlideTimeout);
                    currentIndex = (currentIndex + 1) % sliderItems.length;
                    updateSlider();
                };

                // Go to specific slide (for dot navigation)
                window.goToSlide = function(index) {
                    if (isTransitioning || index === currentIndex) return;
                    clearTimeout(autoSlideTimeout);
                    currentIndex = index;
                    updateSlider();
                };

                // Initialize slider
                function initSlider() {
                    // Configure videos
                    sliderItems.forEach(item => {
                        const video = item.querySelector('video');
                        if (video) {
                            // Remove loop attribute
                            video.removeAttribute('loop');

                            // Make sure onended is properly set
                            video.onended = window.nextSlide;
                        }
                    });

                    // Show first active slide
                    updateSlider();
                }

                // Update slider to current position
                function updateSlider() {
                    isTransitioning = true;

                    // Stop all playing videos
                    sliderItems.forEach(item => {
                        const video = item.querySelector('video');
                        if (video) {
                            video.pause();
                        }
                    });

                    // Update track position
                    sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;

                    // Update navigation dots
                    dots.forEach((dot, index) => {
                        if (index === currentIndex) {
                            dot.classList.add('opacity-90');
                            dot.classList.remove('opacity-50');
                        } else {
                            dot.classList.add('opacity-50');
                            dot.classList.remove('opacity-90');
                        }
                    });

                    // Schedule next slide
                    scheduleNextSlide();

                    // End transition after animation duration
                    setTimeout(() => {
                        isTransitioning = false;
                    }, 500); // Should match CSS transition duration (duration-500)
                }

                // Schedule next slide based on content type
                function scheduleNextSlide() {
                    clearTimeout(autoSlideTimeout);

                    const currentSlide = sliderItems[currentIndex];
                    const video = currentSlide.querySelector('video');

                    if (video) {
                        // If it's a video, set to start from beginning
                        video.currentTime = 0;
                        video.play();
                        // No need to add listener here as we use the onended attribute
                        console.log("Video started, waiting for it to end before going to next slide");
                    } else {
                        // If it's an image, use specified duration or default 6000ms
                        const img = currentSlide.querySelector('img');
                        const duration = img && img.dataset.duration ? parseInt(img.dataset.duration) : 6000;

                        console.log("Image displayed, will change in " + duration / 1000 + " seconds");
                        autoSlideTimeout = setTimeout(window.nextSlide, duration);
                    }
                }

                // Start slider
                initSlider();
            });
        </script>
        <div class="max-w-[80%] xl:max-w-[1224px] mx-auto py-20">
            <div class="flex flex-col gap-6">
                <div class="flex justify-between items-center">
                    <h2 class="titulo-home">Productos</h2>
                    <a href="{{ route('categorias') }}" class="xl:btn-secondary-home-largo btn-secondary-home xl:w-[172px] font-medium">VER TODOS</a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 w-full gap-6">
                    @foreach ($categorias as $categoria)
                        <div
                            class="w-full xl:h-[392px] xl:min-w-[380px] relative transform hover:scale-[1.02] transition-transform duration-300">
                            <a href=""
                                class="block group h-full w-full overflow-hidden relative rounded-md shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img src="{{ asset('storage/' . $categoria->path) }}" alt="{{ $categoria->titulo }}"
                                    class="w-full h-full object-cover transition duration-300 ease-in-out">
                                <p
                                    class="absolute bottom-0 left-0 right-0 text-[26px] font-medium text-center pb-6 text-black drop-shadow-md group-hover:translate-y-1 transition duration-300">
                                    {{ strtoupper($categoria->titulo) }}
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-13.5">
            <img src="{{ asset('storage/' . $contenido->path) }}" alt="Contenido de la pagina"
                class="lg:w-1/2 h-[500px] lg:h-[600px] object-cover opacity-0 -translate-x-20 transition-all duration-2000 ease-in-out scroll-fade-left">

            <div
                class="lg:w-1/2 pr-[5%] pl-[5%] lg:pl-[0%] lg:pr-[71px] lg:mt-20 flex flex-col gap-4 lg:gap-9 opacity-0 translate-x-20 transition-all duration-2000 ease-in-out scroll-fade-right items-center lg:items-start">
                <h2 class="titulo-home">{{ $contenido->titulo }}</h2>
                <div class="custom-summernote text-gray-500 text-center lg:text-left">
                    <p>{!! $contenido->descripcion !!}</p>
                </div>
                <a href="{{ route('nosotros') }}"
                    class="btn-primary-home flex justify-center items-center text-center mb-2 font-medium">MAS INFO</a>
            </div>
        </div>

    </div>
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto py-20 mb-4.5">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h2 class="titulo-home">Novedades</h2>
                <a href="{{ route('categorias') }}" class="btn-secondary-home xl:btn-secondary-home-largo w-[172px] font-medium">VER TODAS</a>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 w-full gap-6">
                @foreach ($novedades as $novedad)
                    <div class="transform hover:scale-[1.02] transition duration-300 hover:shadow-xl">
                        <x-tarjeta-novedades :novedad="$novedad" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', '-translate-x-20',
                            'translate-x-20');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.scroll-fade-left, .scroll-fade-right').forEach(el => {
                observer.observe(el);
            });
        });
    </script>

@endsection
