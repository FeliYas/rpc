@extends('layouts.dashboard')
@section('title', 'Facturas')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Facturas</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <!-- Botón para abrir la modal -->
            <div class="flex justify-start items-center mb-6 mt-4">
                <button class="btn-primary flex items-center gap-2" onclick="openModal('createFacturaModal')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Agregar Factura
                </button>
            </div>

            <!-- Aquí irá la tabla de facturas en el futuro -->
            <div class="bg-white rounded-lg shadow-2xl max-w-screen overflow-x-auto">
                <table class="w-full">
                    <!-- Contenido de la tabla se agregará después -->
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para crear factura -->
    <div id="createFacturaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('createFacturaModal')" id="facturaModalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-2xl z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="facturaModalContent">
            <form id="createFacturaForm" action="{{ route('facturas.store') }}" method="POST"
                class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                @csrf

                <!-- Header -->
                <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                    <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Crear Factura
                    </h2>
                </div>

                <!-- Formulario -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Fecha -->
                        <div class="mb-4">
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input type="date" id="fecha" name="fecha"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>

                        <!-- Tipo de Factura -->
                        <div class="mb-4">
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                                Factura</label>
                            <select id="tipo" name="tipo"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                                <option value="">Seleccione un tipo</option>
                                <option value="Factura A">Factura A</option>
                                <option value="Factura B">Factura B</option>
                                <option value="Factura C">Factura C</option>
                                <option value="Factura E">Factura E</option>
                            </select>
                        </div>

                        <!-- Punto de Venta -->
                        <div class="mb-4">
                            <label for="puntoventa" class="block text-sm font-medium text-gray-700 mb-1">Punto de
                                Venta</label>
                            <input type="text" id="puntoventa" name="puntoventa"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>

                        <!-- Número de Factura -->
                        <div class="mb-4">
                            <label for="nrofactura" class="block text-sm font-medium text-gray-700 mb-1">Número de
                                Factura</label>
                            <input type="text" id="nrofactura" name="nrofactura"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>

                        <!-- Proveedor -->
                        <div class="mb-4">
                            <label for="proveedor_id" class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                            <select id="proveedor_id" name="proveedor_id"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->denominacion }}
                                        ({{ $proveedor->dni }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Cambio -->
                        <div class="mb-4">
                            <label for="tipo_cambio" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cambio
                                (ARS/USD)</label>
                            <input type="number" id="tipo_cambio" name="tipo_cambio" step="0.0001"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Detalle de Factura</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Monto Gravado (en USD) -->
                        <div class="mb-4">
                            <label for="gravado" class="block text-sm font-medium text-gray-700 mb-1">Monto Gravado
                                (USD)</label>
                            <input type="number" id="gravado" name="gravado" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required oninput="calcularTotal()">
                        </div>

                        <!-- Porcentaje de IVA -->
                        <div class="mb-4">
                            <label for="iva_porcentaje" class="block text-sm font-medium text-gray-700 mb-1">IVA</label>
                            <select id="iva_porcentaje" name="iva_porcentaje"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required onchange="calcularTotal()">
                                <option value="">Seleccione un porcentaje</option>
                                <option value="21.00">21%</option>
                                <option value="10.50">10.5%</option>
                                <option value="27.00">27%</option>
                            </select>
                        </div>

                        <!-- Monto de IVA (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="iva_monto" class="block text-sm font-medium text-gray-700 mb-1">Monto IVA
                                (USD)</label>
                            <input type="number" id="iva_monto" name="iva_monto" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Subtotal en USD (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-1">Subtotal
                                (USD)</label>
                            <input type="number" id="subtotal" name="subtotal" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100" readonly>
                        </div>

                        <!-- Total en ARS (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="importe_total" class="block text-sm font-medium text-gray-700 mb-1">Importe Total
                                (ARS)</label>
                            <input type="number" id="importe_total" name="importe_total" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 font-bold text-lg"
                                readonly>
                        </div>
                    </div>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createFacturaModal')"
                        class="btn-secondary px-4 py-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary px-4 py-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar Factura
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para abrir la modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = document.getElementById('facturaModalContent');

            // Mostrar la modal
            modal.classList.remove('hidden');

            // Aplicar animación después de un pequeño retraso
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Establecer fecha actual por defecto
            document.getElementById('fecha').valueAsDate = new Date();

            // Establecer tipo de cambio por defecto (puedes ajustar según necesites)
            document.getElementById('tipo_cambio').value = "1100";
        }

        // Función para cerrar la modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = document.getElementById('facturaModalContent');

            // Primero animación de salida
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            // Luego ocultar después de la animación
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Función para calcular el total
        function calcularTotal() {
            const gravado = parseFloat(document.getElementById('gravado').value) || 0;
            const ivaPorcentaje = parseFloat(document.getElementById('iva_porcentaje').value) || 0;
            const tipoCambio = parseFloat(document.getElementById('tipo_cambio').value) || 0;

            // Calcular monto del IVA
            const ivaMonto = gravado * (ivaPorcentaje / 100);
            document.getElementById('iva_monto').value = ivaMonto.toFixed(2);

            // Calcular subtotal en USD
            const subtotal = gravado + ivaMonto;
            document.getElementById('subtotal').value = subtotal.toFixed(2);

            // Calcular importe total en ARS
            const importeTotal = subtotal * tipoCambio;
            document.getElementById('importe_total').value = importeTotal.toFixed(2);
        }

        // Inicializar evento de input para tipo de cambio
        document.getElementById('tipo_cambio').addEventListener('input', calcularTotal);
    </script>
@endsection
