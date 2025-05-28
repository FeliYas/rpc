@extends('layouts.guest')
@section('title', 'Zona Privada')

@section('content')
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
        <div class="hidden lg:block h-[120px]">
            <div class="text-black py-4">
                <a href="{{ route('home') }}" class="hover:underline transition-all duration-300">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a href="" class="hover:underline transition-all duration-300 text-gray-600">Carrito</a>
            </div>
        </div>

        @if (count(session('carrito', [])) > 0)
            <div class="py-5 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-main-color text-white flex-1 items-center justify-between w-full h-[70px]">
                        <th class="py-4"></th>
                        <th class="text-center lg:text-left px-10 lg:px-3 font-medium">Codigo</th>
                        <th class="text-start lg:text-left px-10 lg:px-3 font-medium">Producto</th>
                        <th class="text-center w-[90px] px-10 lg:px-3 font-medium">Unidad de venta</th>
                        <th class="text-center w-[90px] px-10 lg:px-3 font-medium">Precio <br>x unidad</th>
                        <th class="text-center w-[150px] px-10 lg:px-3 font-medium">Precio x unidad de venta</th>
                        <th class="text-center px-10 lg:px-3 font-medium">Cantidad</th>
                        <th class="text-center w-25 px-10 lg:px-3 font-medium">Total</th>
                        <th class="px-10 lg:px-3 font-medium"></th>
                    </thead>
                    <tbody>
                        @foreach (session('carrito', []) as $index => $item)
                            @php
                                // Extraer el número de la unidad de venta (por ejemplo: "10 litros" => 10)
                                preg_match('/\d+/', $item['unidad_venta'] ?? '1', $match);
                                $unidadStep = isset($match[0]) ? (int) $match[0] : 1;

                                $precioFloat = floatval(str_replace([',', '.'], ['', '.'], $item['precio'] ?? '0'));
                                $precioUnidadVenta = number_format($unidadStep * $precioFloat, 2, ',', '.');
                            @endphp
                            <tr class="bg-white border-b text-gray-700 border-gray-100 hover:bg-gray-50 h-[100px]">
                                <td class="w-1/12 text-center">
                                    @if (!empty($item['imagen']))
                                        <img src="{{ asset('storage/' . $item['imagen']) }}" alt="Imagen del producto"
                                            class="mt-2 w-[85px] h-[85px] object-content">
                                    @else
                                        <img src="{{ asset('storage/images/placeholder.png') }}" alt="Imagen del producto"
                                            class="mt-2 w-[85px] h-[85px] object-content">
                                    @endif
                                </td>
                                <td class="text-left pr-4 font-semibold">{{ $item['codigo'] ?? '—' }}</td>
                                <td class="text-left pr-4 max-w-[250px]">{{ $item['titulo'] ?? '—' }}</td>
                                <td class="text-center pr-4">{{ $item['unidad_venta'] ?? '—' }}</td>
                                <td class="text-center pr-4">${{ number_format($item['precio'], 2, ',', '.') ?? '0' }}</td>
                                <td class="text-center pr-4">${{ $precioUnidadVenta }}</td>
                                <td class="text-center pr-4">
                                    <input type="number" min="{{ $unidadStep }}" step="{{ $unidadStep }}"
                                        value="{{ floor($item['cantidad'] / $unidadStep) * $unidadStep }}"
                                        data-unidad="{{ $unidadStep }}" data-precio="{{ $item['precio'] }}"
                                        data-categoria-id="{{ $item['categoria_id'] ?? '' }}"
                                        class="cantidad-carrito w-16 text-center border border-gray-200 py-2"
                                        data-index="{{ $index }}" />
                                </td>
                                <td class="text-center">${{ number_format($item['total'] ?? 0, 2, ',', '.') }}</td>
                                <td class="flex items-center justify-end h-[100px]">
                                    <button type="button"
                                        class="eliminar-item w-[45px] h-[45px] flex items-center justify-center gap-1 border border-main-color transform transition-all duration-300 cursor-pointer"
                                        data-index="{{ $index }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            viewBox="0 0 25 25" fill="none">
                                            <path
                                                d="M6.78531 9.071L7.92831 20.5H17.0703L18.2133 9.071M13.9993 16V11M10.9993 16V11M5.07031 6.786H9.64231M9.64231 6.786L10.0243 5.257C10.0785 5.04075 10.2034 4.84881 10.3791 4.71166C10.5548 4.5745 10.7714 4.50001 10.9943 4.5H14.0043C14.2272 4.50001 14.4438 4.5745 14.6195 4.71166C14.7953 4.84881 14.9201 5.04075 14.9743 5.257L15.3563 6.786M9.64231 6.786H15.3563M15.3563 6.786H19.9283"
                                                stroke="#0D8141" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div class="py-5 flex text-center items-center">
                <a href="{{ route('productos.zonaprivada') }}" class="btn-secondary-home-largo w-90 font-medium">SEGUIR
                    COMPRANDO</a>
            </div>
        @else
            <div class="py-10 text-center">
                <div class="bg-gray-50 p-8 rounded-lg mb-8">
                    <h3 class="text-xl font-bold text-gray-700">No hay productos en su carrito</h3>
                    <p class="text-gray-600 mb-4">Agregue productos a su carrito para realizar un pedido</p>
                    <a href="{{ route('productos.zonaprivada') }}" class="btn-primary-home-largo inline-block">BUSCAR
                        PRODUCTOS</a>
                </div>
            </div>
        @endif

        @if (count(session('carrito', [])) > 0)
            <!-- Formulario para enviar el pedido -->
            <form id="pedidoForm" action="{{ route('pedido.procesar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="py-8 grid lg:grid-cols-2 gap-4">
                    <div class="border border-gray-200">
                        <h4 class="bg-gray-100 p-6 text-xl font-bold">Informacion importante</h4>
                        <div class="text-gray-500 custom-summernote p-6">
                            <p>{!! $info->descripcion !!}</p>
                        </div>
                    </div>
                    <div class="border border-gray-200 overflow-hidden">
                        <h4 class="text-xl font-bold bg-gray-100 p-6">Entrega</h4>
                        <div class="p-6 space-y-4 text-gray-500">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="entrega" value="retiro" class="accent-green-600" required>
                                <span>Retiro en fábrica</span>
                            </label>
                            <label class="flex items-center space-x-2 ">
                                <input type="radio" name="entrega" value="transporte" class="accent-green-600" required>
                                <span>Transporte al interior</span>
                            </label>
                        </div>
                    </div>
                    <div class="w-full lg:py-2">
                        <h4 class="text-xl font-bold p-6">Escribinos un mensaje</h4>
                        <textarea name="mensaje" id="mensaje" cols="30" rows="10"
                            class="border border-gray-300 w-full p-4 text-gray-500"
                            placeholder="Dias especiales de entrega, cambios de domicilio, expresos, requerimientos especiales en la mercaderia, exenciones."></textarea>
                    </div>
                    <!-- En la sección "Pedido" (resumen) -->
                    <div class="border border-gray-200 mb-3.5">
                        <h4 class="bg-gray-100 p-6 text-xl font-semibold">Pedido</h4>
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                @php
                                    $subtotal = 0;
                                    $descuentoCliente = 0;
                                    $descuentoProductos = 0;
                                    $totalSinIva = 0;
                                    $ivaImporte = 0;
                                    $totalConIva = 0;

                                    foreach (session('carrito', []) as $item) {
                                        $subtotal += $item['subtotal'] ?? 0;
                                        $descuentoCliente += $item['descuento_cliente_valor'] ?? 0;
                                        $descuentoProductos += $item['descuento_aplicado_valor'] ?? 0;
                                        $totalSinIva += $item['total_sin_iva'] ?? 0;
                                        $ivaImporte += $item['iva_importe'] ?? 0;
                                        $totalConIva += $item['total'] ?? 0;
                                    }
                                @endphp

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-semibold">${{ number_format($subtotal, 2, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between text-[#308C05]">
                                    <span>Descuento cliente ({{ Auth::guard('cliente')->user()->descuento }}%):</span>
                                    <span>-${{ number_format($descuentoCliente, 2, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between text-[#308C05]">
                                    <span>Descuento productos:</span>
                                    <span>-${{ number_format($descuentoProductos, 2, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span>Subtotal con descuentos:</span>
                                    <span>${{ number_format($totalSinIva, 2, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>IVA (21%):</span>
                                    <span>${{ number_format($ivaImporte, 2, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between pt-4 border-t border-gray-200 font-bold text-lg">
                                    <span>TOTAL:</span>
                                    <span>${{ number_format($totalConIva, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div></div>
                    <div class="flex justify-between items-center gap-6">
                        <!-- Campo oculto para almacenar el token de reCAPTCHA -->
                        <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                        <button type="button" id="cancelarPedido" class="btn-secondary-home-largo w-full">CANCELAR
                            PEDIDO</button>
                        <button type="button" id="submitBtn" class="btn-primary-home-largo w-full">REALIZAR
                            PEDIDO</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <!-- Script de reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LfunycrAAAAAAUdd5QxBm7AeK_9ec2Phizdo6LA"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ======= 1. Configuración de Toastr para mensajes de error y éxito =======
            // Configuración global de Toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000,
                extendedTimeOut: 2000,
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };

            // ======= 2. Modal de confirmación para eliminar productos =======
            // HTML para la modal de confirmación de eliminación
            const deleteModalHTML = `
        <div id="deleteModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
            <!-- Overlay con animación -->
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
                onclick="closeDeleteModal()" id="deleteModalOverlay"></div>

            <!-- Modal con animación -->
            <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
                id="deleteModal">
                <div class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                    <!-- Header con icono de advertencia -->
                    <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-center">
                        <div class="rounded-full bg-red-100 p-3 inline-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">¿Estás seguro de que querés
                            eliminar este producto?</h2>
                        <p class="text-gray-600 text-center mb-6">Esta acción no se puede deshacer.</p>
                    </div>

                    <!-- Footer con botones -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-center gap-4">
                        <button type="button" onclick="closeDeleteModal()"
                            class="bg-gray-200 text-gray-800 px-5 py-2 rounded cursor-pointer hover:bg-gray-300 transition-colors duration-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                        <button type="button" id="confirmDeleteBtn"
                            class="bg-red-600 text-white px-5 py-2 rounded cursor-pointer hover:bg-red-700 transition-colors duration-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

            // ======= 3. Modal de confirmación para pedido enviado =======
            // HTML para la modal de confirmación de pedido
            const orderConfirmModalHTML = `
        <div id="orderConfirmModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
            <!-- Overlay con animación -->
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
                onclick="closeOrderConfirmModal()" id="orderConfirmModalOverlay"></div>

            <!-- Modal con animación -->
            <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
                id="orderConfirmModal">
                <div class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                    <!-- Header con icono de éxito -->
                    <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex justify-center">
                        <div class="rounded-full bg-green-100 p-3 inline-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Pedido confirmado</h2>
                        <p class="text-gray-600 text-center mb-2">Su pedido <span id="orderNumber" class="font-medium"></span> está en proceso y te avisaremos por email cuando
                            esté listo.</p>
                        <p class="text-gray-600 text-center mb-6">Si tienes alguna pregunta, no dudes en contactarnos.</p>
                    </div>

                    <!-- Footer con botón -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-center">
                        <a href="/zonaprivada/productos" 
                            class="bg-main-color text-white px-5 py-2 rounded cursor-pointer hover:opacity-90 transition-colors duration-200 text-center">
                            VOLVER A PRODUCTOS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;

            // Agregar las modales al DOM
            document.body.insertAdjacentHTML('beforeend', deleteModalHTML);
            document.body.insertAdjacentHTML('beforeend', orderConfirmModalHTML);

            // ======= Funciones para manejar las modales =======
            // Función para mostrar la modal de eliminar
            window.showDeleteModal = function(index) {
                const modalWrapper = document.getElementById('deleteModalWrapper');
                const modal = document.getElementById('deleteModal');
                const confirmBtn = document.getElementById('confirmDeleteBtn');

                // Guardar el índice del elemento a eliminar
                confirmBtn.setAttribute('data-index', index);

                // Mostrar el wrapper primero
                modalWrapper.classList.remove('hidden');

                // Luego animar la modal (pequeño delay para que la animación sea visible)
                setTimeout(() => {
                    modal.classList.remove('scale-95', 'opacity-0');
                    modal.classList.add('scale-100', 'opacity-100');
                }, 10);

                // Configurar el botón de confirmar
                confirmBtn.onclick = function() {
                    const itemIndex = this.getAttribute('data-index');
                    eliminarProducto(itemIndex);
                    closeDeleteModal();
                };
            };

            // Función para cerrar la modal de eliminar
            window.closeDeleteModal = function() {
                const modalWrapper = document.getElementById('deleteModalWrapper');
                const modal = document.getElementById('deleteModal');

                // Animar la salida
                modal.classList.remove('scale-100', 'opacity-100');
                modal.classList.add('scale-95', 'opacity-0');

                // Ocultar el wrapper después de la animación
                setTimeout(() => {
                    modalWrapper.classList.add('hidden');
                }, 300);
            };

            // Función para mostrar la modal de confirmación de pedido
            window.showOrderConfirmModal = function(orderNumber) {
                const modalWrapper = document.getElementById('orderConfirmModalWrapper');
                const modal = document.getElementById('orderConfirmModal');
                const orderNumberEl = document.getElementById('orderNumber');

                // Establecer el número de orden
                if (orderNumber) {
                    orderNumberEl.textContent = '#' + orderNumber;
                }

                // Mostrar el wrapper primero
                modalWrapper.classList.remove('hidden');

                // Luego animar la modal
                setTimeout(() => {
                    modal.classList.remove('scale-95', 'opacity-0');
                    modal.classList.add('scale-100', 'opacity-100');
                }, 10);
            };

            // Función para cerrar la modal de confirmación de pedido
            window.closeOrderConfirmModal = function() {
                const modalWrapper = document.getElementById('orderConfirmModalWrapper');
                const modal = document.getElementById('orderConfirmModal');

                // Animar la salida
                modal.classList.remove('scale-100', 'opacity-100');
                modal.classList.add('scale-95', 'opacity-0');

                // Ocultar el wrapper después de la animación
                setTimeout(() => {
                    modalWrapper.classList.add('hidden');
                }, 300);
            };

            // ======= 4. Modificaciones a las funciones existentes =======

            // Reemplazar la función eliminarProducto para usar Toastr
            window.eliminarProducto = function(index) {
                fetch('/zonaprivada/eliminar-del-carrito', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            index: index
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Usar Toastr para mostrar éxito
                            toastr.success('Producto eliminado del carrito correctamente');

                            // Recargar la página después de un breve retraso
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Usar Toastr para mostrar error
                            toastr.error(data.message || 'Error al eliminar el producto');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Error al procesar la solicitud');
                    });
            };

            // Reemplazar la función actualizarCantidad para usar Toastr
            window.actualizarCantidad = function(index, cantidad) {
                fetch('/zonaprivada/actualizar-carrito', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            index: index,
                            cantidad: cantidad
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Usar Toastr para mostrar éxito
                            toastr.success('Cantidad actualizada correctamente');

                            // Recargar la página después de un breve retraso
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Usar Toastr para mostrar error
                            toastr.error(data.message || 'Error al actualizar el carrito');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Error al procesar la solicitud');
                    });
            };

            // ======= 5. Conectar los botones existentes con las nuevas funciones =======

            // Botones eliminar
            const botonesEliminar = document.querySelectorAll('.eliminar-item');
            if (botonesEliminar.length > 0) {
                botonesEliminar.forEach(boton => {
                    boton.addEventListener('click', function(e) {
                        e.preventDefault();
                        const index = this.getAttribute('data-index');
                        showDeleteModal(index);
                    });
                });
            }

            // Modificar la función de submit del pedido
            // Modificar la función de submit del pedido
            const submitBtn = document.getElementById('submitBtn');
            const pedidoForm = document.getElementById('pedidoForm');

            if (submitBtn && pedidoForm) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Validar la forma de entrega (requerido)
                    const radioEntrega = document.querySelectorAll('input[name="entrega"]:checked');
                    if (radioEntrega.length === 0) {
                        toastr.error('Por favor, seleccione una forma de entrega');
                        return;
                    }

                    // Mostrar algún indicador de carga
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Procesando...';

                    // Activar reCAPTCHA
                    grecaptcha.ready(function() {
                        grecaptcha.execute('6LfunycrAAAAAAUdd5QxBm7AeK_9ec2Phizdo6LA', {
                            action: 'submit_pedido'
                        }).then(function(token) {
                            // Guardar el token en el campo oculto
                            document.getElementById('recaptchaResponse').value = token;

                            // Convertir el formulario a FormData para envío con AJAX
                            const formData = new FormData(pedidoForm);

                            // Enviar el formulario con AJAX
                            fetch(pedidoForm.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content'),
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => {
                                    // Verificar si la respuesta es JSON antes de parsearla
                                    const contentType = response.headers.get(
                                        'content-type');
                                    if (contentType && contentType.includes(
                                            'application/json')) {
                                        return response.json();
                                    } else {
                                        // Si no es JSON, lanzar un error con el texto de la respuesta
                                        return response.text().then(text => {
                                            throw new Error(
                                                `La respuesta del servidor no es JSON: ${text}`
                                            );
                                        });
                                    }
                                })
                                .then(data => {
                                    submitBtn.disabled = false;
                                    submitBtn.textContent = 'REALIZAR PEDIDO';

                                    if (data.success) {
                                        // En lugar de mostrar la modal, redirigir a la página de confirmación
                                        window.location.href =
                                            `/pedido/confirmacion/${data.orderNumber}`;
                                    } else {
                                        toastr.error(data.message ||
                                            'Error al procesar el pedido');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    toastr.error('Error al procesar la solicitud: ' +
                                        error.message);

                                    submitBtn.disabled = false;
                                    submitBtn.textContent = 'REALIZAR PEDIDO';
                                });
                        });
                    });
                });
            }

            // ======= 6. Actualizar las funciones de los inputs de cantidad =======

            const inputsCantidad = document.querySelectorAll('.cantidad-carrito');
            if (inputsCantidad.length > 0) {
                inputsCantidad.forEach(input => {
                    input.addEventListener('change', function() {
                        const index = this.getAttribute('data-index');
                        const unidad = parseInt(this.getAttribute('data-unidad')) || 1;
                        let cantidad = parseInt(this.value);

                        // Validar que la cantidad sea válida y un múltiplo de la unidad de venta
                        if (isNaN(cantidad) || cantidad < unidad) {
                            cantidad = unidad;
                            this.value = unidad;
                            toastr.warning(`La cantidad mínima es ${unidad}`);
                        } else {
                            // Redondear al múltiplo más cercano de la unidad de venta
                            const resto = cantidad % unidad;
                            if (resto !== 0) {
                                cantidad = Math.round(cantidad / unidad) * unidad;
                                this.value = cantidad;
                                toastr.info(
                                    `La cantidad se ha ajustado a ${cantidad} (múltiplo de ${unidad})`
                                );
                            }
                        }

                        actualizarCantidad(index, cantidad);
                    });
                });
            }

            // ======= 7. Comprobar si hay un mensaje de éxito que mostrar (para cargar la modal si viene de redirección) =======
            // Esta parte sería útil si vienes de una redirección por POST o si tienes parámetros en la URL

            const urlParams = new URLSearchParams(window.location.search);
            const orderSuccess = urlParams.get('order_success');
            const orderNumber = urlParams.get('order_number');

            if (orderSuccess === 'true') {
                showOrderConfirmModal(orderNumber || '');
            }
        });
    </script>
@endsection
