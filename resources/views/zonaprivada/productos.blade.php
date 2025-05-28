@extends('layouts.guest')
@section('title', 'Zona Privada')

@section('content')
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
        <div class="hidden lg:block h-[120px]">
            <div class="text-black py-4">
                <a href="{{ route('home') }}" class="hover:underline transition-all duration-300">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a href="" class="hover:underline transition-all duration-300 font-light">Productos</a>
            </div>
        </div>

        <div class="mt-10 lg:mt-0 mb-10">
            <div class="flex flex-col lg:flex-row gap-4 lg:justify-between items-center mb-5 ">
                <p class="font-bold text-2xl">Tu descuento exclusivo: <span
                        class="text-main-color font-bold text-2xl">{{ Auth::guard('cliente')->user()->descuento }}%</span>
                </p>
                <div
                    class="border border-gray-300 px-5 py-2.5 flex justify-between  rounded shadow items-center text-center lg:w-100 text-gray-400">
                    <p>Filtrar por categoria:</p>
                    <div>
                        <select id="categoria-filter" onchange="filtrarPorCategoria()" class="text-right">
                            <option value="">Todas</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" class="text-black">{{ $categoria->titulo }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
            <!-- Contenedor principal de la tabla con detección de dispositivo móvil -->
            <div class="md:overflow-visible overflow-x-auto mb-50">
                <table class="w-full">
                    <thead class="bg-main-color text-white flex-1 items-center justify-between w-full h-[70px]">
                        <th class="py-4 hidden lg:block"></th>
                        <th class="text-center lg:text-left px-10 lg:px-3 font-medium">Codigo</th>
                        <th class="text-start lg:text-left px-10 lg:px-3 font-medium">Producto</th>
                        <th class="text-center w-[90px] px-10 lg:px-3 font-medium">Unidad de venta</th>
                        <th class="text-center w-[90px] px-10 lg:px-3 font-medium">Precio <br>x unidad</th>
                        <th class="text-center w-[150px] px-10 lg:px-3 font-medium">Precio x unidad de venta</th>
                        <th class="text-center px-10 lg:px-3 font-medium">Descuento</th>
                        <th class="text-center px-10 lg:px-3 font-medium">Cantidad</th>
                        <th class="text-center w-25 px-10 lg:px-3 font-medium">Total</th>
                        <th class="px-10 lg:px-3 font-medium"></th>
                    </thead>
                    <tbody class="flex-1 items-center justify-between w-full text-center">
                        @foreach ($productos as $producto)
                            <tr class="bg-white border-b text-gray-700 border-gray-100 hover:bg-gray-50 h-[100px]">
                                <td class="text-center hidden lg:flex items-center justify-center ">
                                    @php
                                        $imagen = $producto->imagenes->first()?->path ?? null;
                                    @endphp
                                    @if ($imagen)
                                        <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen del producto"
                                            class="mt-2 w-[85px] h-[85px] object-content">
                                    @else
                                        <img src="{{ asset('storage/images/placeholder.png') }}" alt="Imagen del producto"
                                            class="mt-2 w-[85px] h-[85px] object-content">
                                    @endif
                                </td>

                                <td class="text-left px-3 lg:text-left pr-4 font-semibold">{{ $producto->codigo ?? '—' }}
                                </td>
                                <td class="text-left px-3 lg:pr-4 lg:max-w-[250px] ">{{ $producto->titulo ?? '—' }}</td>

                                @php
                                    // Extraer la cantidad numérica desde la unidad (por ejemplo: "5 litros" => 5)
                                    preg_match('/\d+/', $producto->unidad ?? '', $match);
                                    $cantidad = isset($match[0]) ? (int) $match[0] : 0;

                                    // Procesar el precio eliminando puntos de miles y usando punto decimal
                                    $precioRaw = str_replace(['.', ','], ['', '.'], $producto->precio ?? '0');
                                    $precioFloat = is_numeric($precioRaw) ? floatval($precioRaw) : 0;

                                    // Calcular el total
                                    $total = $cantidad * $precioFloat;
                                @endphp

                                <td class="text-center pr-4">
                                    {{ $producto->unidad ?? '—' }}
                                </td>

                                <td class="text-center pr-4">
                                    {{ $producto->precio ? '$' . number_format($producto->precio, 2, ',', '.') : '—' }}
                                </td>

                                <td class="text-center pr-4">
                                    ${{ number_format($total, 2, ',', '.') }}
                                </td>


                                <!-- Reemplaza la sección actual del tooltip/modal con este código -->
                                <td>
                                    <div class="group relative flex items-center justify-center text-center ">
                                        <i class="fa-solid fa-circle-info text-main-color cursor-pointer"
                                            style="font-size: 24px !important"></i>
                                        <div
                                            class="absolute z-50 hidden group-hover:flex flex-col text-left bg-white shadow-xl rounded-xl p-4 w-72 -left-32 top-10 border border-gray-200">
                                            <!-- DESCUENTOS -->
                                            <h4 class="font-semibold text-main-color mb-2">Descuentos</h4>
                                            <ul class="text-sm text-gray-700 mb-3 list-disc pl-4">
                                                @forelse ($producto->descuentos as $descuento)
                                                    <li>
                                                        Mínimo: {{ $descuento->cantidad_minima }} –
                                                        Descuento: {{ $descuento->descuento }}%
                                                    </li>
                                                @empty
                                                    <li>No hay descuentos para este producto.</li>
                                                @endforelse
                                            </ul>

                                            <!-- PRODUCTOS COMBINABLES -->
                                            <h4 class="font-semibold text-main-color mb-2">Productos combinables</h4>
                                            <ul class="text-sm text-gray-700 mb-3 list-disc pl-4">
                                                @php
                                                    $combinables = collect();

                                                    foreach ($producto->descuentos as $descuento) {
                                                        $otros = $descuento->productos->where(
                                                            'id',
                                                            '!=',
                                                            $producto->id,
                                                        );
                                                        $combinables = $combinables->merge($otros);
                                                    }

                                                    $combinables = $combinables->unique('id'); // Evitar duplicados
                                                @endphp

                                                @forelse ($combinables as $combinado)
                                                    <li>{{ $combinado->codigo }} – {{ $combinado->titulo }}</li>
                                                @empty
                                                    <li>No hay productos combinables.</li>
                                                @endforelse
                                            </ul>

                                            <p class="text-sm text-gray-500 italic">
                                                Combiná productos compatibles para alcanzar la cantidad mínima y aprovechar
                                                el
                                                descuento automáticamente. <br>
                                                <span class="underline font-semibold">Importante:</span>
                                                El descuento combinado por la cantidad de productos no se reflejara en el
                                                total de esta tabla, pero si en el carrito al finalizar el pedido
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center pr-4">
                                    <input type="number" min="0" value="0" step="{{ $cantidad }}"
                                        data-categoria-id="{{ $producto->categoria_id }}"
                                        data-unidad="{{ $cantidad }}" data-precio="{{ $producto->precio }}"
                                        class="cantidad-input w-16 text-center border border-gray-200 py-2" />
                                </td>

                                <td class="text-center total-price">$0</td>

                                <td>
                                    <div class="flex items-end justify-end">
                                        <button type="button" data-producto-id="{{ $producto->id }}"
                                            data-categoria-id="{{ $producto->categoria_id }}"
                                            class="w-[45px] h-[45px] flex items-center justify-center gap-1 border border-main-color transform transition-all duration-300 cursor-pointer hover:scale-110">
                                            <span class="text-main-color text-lg">+</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16" fill="none">
                                                <path
                                                    d="M5.00416 16C4.59128 16 4.23795 15.8435 3.94418 15.5304C3.65041 15.2173 3.50327 14.8405 3.50277 14.4C3.50277 13.96 3.64991 13.5835 3.94418 13.2704C4.23845 12.9573 4.59178 12.8005 5.00416 12.8C5.41704 12.8 5.77062 12.9568 6.06489 13.2704C6.35916 13.584 6.50605 13.9605 6.50555 14.4C6.50555 14.84 6.35866 15.2168 6.06489 15.5304C5.77112 15.844 5.41754 16.0005 5.00416 16ZM12.5111 16C12.0982 16 11.7449 15.8435 11.4511 15.5304C11.1573 15.2173 11.0102 14.8405 11.0097 14.4C11.0097 13.96 11.1568 13.5835 11.4511 13.2704C11.7454 12.9573 12.0987 12.8005 12.5111 12.8C12.924 12.8 13.2776 12.9568 13.5718 13.2704C13.8661 13.584 14.013 13.9605 14.0125 14.4C14.0125 14.84 13.8656 15.2168 13.5718 15.5304C13.2781 15.844 12.9245 16.0005 12.5111 16ZM4.36607 3.2L6.16774 7.2H11.4226L13.487 3.2H4.36607ZM3.65291 1.6H14.7256C15.0134 1.6 15.2324 1.7368 15.3825 2.0104C15.5326 2.284 15.5389 2.56053 15.4013 2.84L12.7363 7.96C12.5987 8.22667 12.4143 8.43333 12.183 8.58C11.9518 8.72667 11.6983 8.8 11.4226 8.8H5.82992L5.00416 10.4H14.0125V12H5.00416C4.44114 12 4.01575 11.7368 3.72798 11.2104C3.44022 10.684 3.4277 10.1605 3.69045 9.64L4.70388 7.68L2.00139 1.6H0.5V0H2.93975L3.65291 1.6Z"
                                                    fill="#0D8141" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const descuentosProducto = @json($producto->descuentos);
        const descuentoCliente = {{ Auth::guard('cliente')->user()->descuento ?? 0 }};

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

            function filtrarPorCategoria() {
                const categoriaId = document.getElementById('categoria-filter').value;
                const filas = document.querySelectorAll('tbody tr'); // Select all table rows

                filas.forEach(fila => {
                    // Find the add to cart button which contains the categoria-id data attribute
                    const btn = fila.querySelector('button[data-categoria-id]');
                    const filaCategoriaId = btn ? btn.getAttribute('data-categoria-id') : null;

                    // Or use the input which also has the categoria-id
                    const input = fila.querySelector('input[data-categoria-id]');
                    const inputCategoriaId = input ? input.getAttribute('data-categoria-id') : null;

                    // Use either source of the categoria ID
                    const rowCategoriaId = filaCategoriaId || inputCategoriaId;

                    if (!categoriaId || rowCategoriaId === categoriaId) {
                        fila.style.display = ''; // Show the row
                    } else {
                        fila.style.display = 'none'; // Hide the row
                    }
                });
            }

            function formatearPrecio(numero) {
                return numero.toLocaleString('es-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Manejador de eventos para inputs de cantidad
            document.querySelectorAll('.cantidad-input').forEach(input => {
                input.addEventListener('input', function(e) {
                    const unidad = parseInt(this.dataset.unidad);
                    if (!unidad || unidad <= 0) return; // prevención por si falta el valor

                    let valor = parseInt(this.value);
                    if (isNaN(valor) || valor < 0) valor = 0;

                    // redondear al múltiplo más cercano
                    const resto = valor % unidad;
                    if (resto !== 0) {
                        this.value = Math.round(valor / unidad) * unidad;
                        // Usar Toastr para informar del ajuste
                        toastr.info(
                            `La cantidad se ha ajustado a ${this.value} (múltiplo de ${unidad})`
                        );
                    }
                });
            });

            const inputs = document.querySelectorAll('.cantidad-input');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const cantidad = parseFloat(this.value) || 0;
                    const precio = parseFloat(this.dataset.precio) || 0;
                    const tdTotal = this.closest('tr').querySelector('.total-price');

                    // Filtramos los descuentos que son aplicables según la cantidad seleccionada
                    const descuentosAplicables = descuentosProducto.filter(descuento => {
                        return cantidad >= descuento.cantidad_minima;
                    });

                    // Si hay descuentos aplicables, seleccionamos el mayor
                    let descuentoAplicado = 0;
                    if (descuentosAplicables.length > 0) {
                        // Obtenemos el descuento más alto
                        descuentoAplicado = Math.max(...descuentosAplicables.map(descuento =>
                            descuento.descuento));

                        // Mostrar notificación de descuento aplicado
                        toastr.success(
                            `Se ha aplicado un descuento del ${descuentoAplicado}% por cantidad`
                        );
                    }

                    // Total inicial (sin descuento)
                    let total = cantidad * precio;

                    // Aplicar el descuento del cliente
                    const descuentoClienteAplicado = total * (descuentoCliente / 100);
                    const totalConDescuentoCliente = total - descuentoClienteAplicado;

                    // Aplicar el descuento del producto
                    const descuentoProductoAplicado = totalConDescuentoCliente * (
                        descuentoAplicado / 100);
                    const totalConDescuento = totalConDescuentoCliente - descuentoProductoAplicado;

                    // Actualizar el total en la vista CON EL FORMATO CORRECTO
                    tdTotal.textContent = '$' + formatearPrecio(totalConDescuento);
                });
            });
            const categoriaFilter = document.getElementById('categoria-filter');
            if (categoriaFilter) {
                categoriaFilter.addEventListener('change', filtrarPorCategoria);
                // Informar al usuario que el filtro ha sido aplicado
                categoriaFilter.addEventListener('change', function() {
                    const categoriaSeleccionada = this.options[this.selectedIndex].text;
                    if (this.value) {
                        toastr.info(
                            `Filtrando productos por categoría: ${categoriaSeleccionada}`);
                    } else {
                        toastr.info('Mostrando todos los productos');
                    }
                });
            }

            // Event listener para agregar al carrito
            const botonesAgregar = document.querySelectorAll('[data-producto-id]');

            botonesAgregar.forEach(boton => {
                boton.addEventListener('click', function() {
                    const productoId = this.getAttribute('data-producto-id');
                    const fila = this.closest('tr');
                    const cantidadInput = fila.querySelector('.cantidad-input');
                    const cantidad = parseInt(cantidadInput.value);

                    if (cantidad <= 0) {
                        // Reemplazar alert con toastr para error
                        toastr.error('Por favor, seleccione una cantidad válida.');
                        return;
                    }

                    // Recopilar información del producto
                    const codigo = fila.querySelector('td:nth-child(2)').textContent;
                    const titulo = fila.querySelector('td:nth-child(3)').textContent;
                    const unidadVenta = fila.querySelector('td:nth-child(4)')
                        .textContent.trim();
                    const precioUnitario = fila.querySelector('td:nth-child(5)')
                        .textContent
                        .replace('$', '').trim();
                    const precioUnidadVenta = fila.querySelector('td:nth-child(6)')
                        .textContent
                        .replace('$', '').trim();
                    const totalPrecio = fila.querySelector('.total-price').textContent
                        .replace('$',
                            '').trim();



                    // Enviar al servidor
                    agregarAlCarrito(productoId, cantidad, codigo, titulo, unidadVenta,
                        precioUnitario, precioUnidadVenta, totalPrecio);
                });
            });

            function agregarAlCarrito(productoId, cantidad, codigo, titulo, unidadVenta, precioUnitario,
                precioUnidadVenta, totalPrecio) {
                fetch('/zonaprivada/agregar-al-carrito', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute(
                                    'content')
                        },
                        body: JSON.stringify({
                            producto_id: productoId,
                            cantidad: cantidad,
                            codigo: codigo,
                            titulo: titulo,
                            unidad_venta: unidadVenta,
                            precio_unitario: precioUnitario,
                            precio_unidad_venta: precioUnidadVenta,
                            total: totalPrecio
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reemplazar alert con toastr para éxito
                            toastr.success(
                                `${titulo} (${cantidad} unidad${cantidad > 1 ? 'es' : ''}) agregado al carrito correctamente.`
                            );

                            // Opcionalmente actualizar un contador de carrito si tienes uno
                            // Si hay un elemento con la clase 'cart-count' para mostrar número de items
                            const cartCountEl = document.querySelector('.cart-count');
                            if (cartCountEl && data.cartCount) {
                                cartCountEl.textContent = data.cartCount;
                                cartCountEl.classList.remove('hidden'); // Por si estaba oculto
                            }
                        } else {
                            // Reemplazar alert con toastr para error
                            toastr.error('Error al agregar el producto: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Reemplazar alert con toastr para error
                        toastr.error('Error al procesar la solicitud.');
                    });
            }
        });
    </script>
@endsection
