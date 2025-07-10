@extends('layouts.dashboard')
@section('title', 'Facturas')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Facturas</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div class="py-4">
            <!-- Header con título y botón -->
            <div class="flex justify-between items-center mb-6">
                <button class="btn-primary flex items-center gap-2" onclick="openModal('createFacturaModal')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Agregar Factura
                </button>

                <div class="flex gap-3">
                    <button id="verResumenBtn" class="btn-secondary flex items-center gap-2 opacity-50 cursor-not-allowed"
                        disabled onclick="mostrarResumenFacturas()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 11H15M9 15H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Ver Resumen
                    </button>

                    <button id="imprimirMultiplesBtn"
                        class="btn-secondary flex items-center gap-2 opacity-50 cursor-not-allowed" disabled
                        onclick="imprimirMultiples()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 9V2H18V9M6 18H4C3.46957 18 2.96086 17.7893 2.58579 17.4142C2.21071 17.0391 2 16.5304 2 16V11C2 10.4696 2.21071 9.96086 2.58579 9.58579C2.96086 9.21071 3.46957 9 4 9H20C20.5304 9 21.0391 9.21071 21.4142 9.58579C21.7893 9.96086 22 10.4696 22 11V16C22 16.5304 21.7893 17.0391 21.4142 17.4142C21.0391 17.7893 20.5304 18 20 18H18M6 14H18V22H6V14Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Exportar seleccionas a PDF
                    </button>
                </div>
            </div>

            <!-- Filtros por fecha -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label for="fechaDesde" class="text-sm font-medium text-gray-700">Desde:</label>
                        <input type="date" id="fechaDesde"
                            class="border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color text-sm"
                            onchange="filtrarPorFecha()">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="fechaHasta" class="text-sm font-medium text-gray-700">Hasta:</label>
                        <input type="date" id="fechaHasta"
                            class="border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color text-sm"
                            onchange="filtrarPorFecha()">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="filtroProveedor" class="text-sm font-medium text-gray-700">Proveedor:</label>
                        <select id="filtroProveedor"
                            class="border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color text-sm"
                            onchange="filtrarPorFecha()">
                            <option value="">Todos los proveedores</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->denominacion }}">{{ $proveedor->denominacion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button onclick="limpiarFiltros()"
                        class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-white border border-red-700 hover:text-red-700 cursor-pointer transition-colors duration-300 text-sm flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 6H21M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Limpiar Filtros
                    </button>
                    <div class="text-sm text-gray-600">
                        Mostrando <span id="contadorFacturas">{{ count($facturas) }}</span> facturas
                    </div>
                </div>
            </div>

            <!-- Tabla con diseño minimalista -->
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
                            <label for="proveedor_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                            <select id="proveedor_id" name="proveedor_id"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->denominacion }}
                                        ({{ $proveedor->dni }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selector de moneda -->
                        <div class="mb-4">
                            <label for="moneda" class="block text-sm font-medium text-gray-700 mb-1">Moneda</label>
                            <select id="moneda" name="moneda"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required onchange="onMonedaChange()">
                                <option value="ARS" selected>PESOS (ARS)</option>
                                <option value="USD">DÓLARES (USD)</option>
                            </select>
                        </div>

                        <!-- Tipo de Cambio (solo si es USD) -->
                        <div class="mb-4" id="tipoCambioGroup" style="display:none;">
                            <label for="tipo_cambio" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cambio
                                (ARS/USD)</label>
                            <input type="number" id="tipo_cambio" name="tipo_cambio" step="0.0001"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Detalle de Factura</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Monto Gravado (en ARS o USD) -->
                        <div class="mb-4">
                            <label for="gravado" class="block text-sm font-medium text-gray-700 mb-1">Monto Gravado
                                <span id="labelMonedaGravado">(ARS)</span></label>
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
                                <span id="labelMonedaIva">(ARS)</span></label>
                            <input type="text" id="iva_monto" name="iva_monto" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 text-right" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Subtotal (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-1">Subtotal
                                <span id="labelMonedaSubtotal">(ARS)</span></label>
                            <input type="text" id="subtotal" name="subtotal" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 text-right" readonly>
                        </div>

                        <!-- Total (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="importe_total" class="block text-sm font-medium text-gray-700 mb-1">Importe Total
                                <span id="labelMonedaTotal">(ARS)</span></label>
                            <input type="text" id="importe_total" name="importe_total" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 font-bold text-lg text-right"
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

        function onMonedaChange() {
            const moneda = document.getElementById('moneda').value;
            const tipoCambioGroup = document.getElementById('tipoCambioGroup');
            const labelMonedaGravado = document.getElementById('labelMonedaGravado');
            const labelMonedaIva = document.getElementById('labelMonedaIva');
            const labelMonedaSubtotal = document.getElementById('labelMonedaSubtotal');
            const labelMonedaTotal = document.getElementById('labelMonedaTotal');
            if (moneda === 'USD') {
                tipoCambioGroup.style.display = '';
                labelMonedaGravado.textContent = '(USD)';
                labelMonedaIva.textContent = '(USD)';
                labelMonedaSubtotal.textContent = '(USD)';
                labelMonedaTotal.textContent = '(ARS)';
            } else {
                tipoCambioGroup.style.display = 'none';
                labelMonedaGravado.textContent = '(ARS)';
                labelMonedaIva.textContent = '(ARS)';
                labelMonedaSubtotal.textContent = '(ARS)';
                labelMonedaTotal.textContent = '(ARS)';
            }
            calcularTotal();
        }

        // Función para manejar el cambio de moneda en la modal de edición
        function onMonedaChangeEdit() {
            const moneda = document.getElementById('edit_moneda').value;
            const tipoCambioGroup = document.getElementById('edit_tipoCambioGroup');
            const labelMonedaGravado = document.querySelector('label[for="edit_gravado"] span');
            const labelMonedaIva = document.querySelector('label[for="edit_iva_monto"] span');
            const labelMonedaSubtotal = document.querySelector('label[for="edit_subtotal"] span');
            const labelMonedaTotal = document.querySelector('label[for="edit_importe_total"] span');
            if (moneda === 'USD') {
                tipoCambioGroup.style.display = '';
                if (labelMonedaGravado) labelMonedaGravado.textContent = '(USD)';
                if (labelMonedaIva) labelMonedaIva.textContent = '(USD)';
                if (labelMonedaSubtotal) labelMonedaSubtotal.textContent = '(USD)';
                if (labelMonedaTotal) labelMonedaTotal.textContent = '(ARS)';
            } else {
                tipoCambioGroup.style.display = 'none';
                if (labelMonedaGravado) labelMonedaGravado.textContent = '(ARS)';
                if (labelMonedaIva) labelMonedaIva.textContent = '(ARS)';
                if (labelMonedaSubtotal) labelMonedaSubtotal.textContent = '(ARS)';
                if (labelMonedaTotal) labelMonedaTotal.textContent = '(ARS)';
            }
            calcularTotalEdit();
        }

        // Función para calcular el total
        function calcularTotal() {
            const moneda = document.getElementById('moneda').value;
            const gravado = parseFloat(document.getElementById('gravado').value) || 0;
            const ivaPorcentaje = parseFloat(document.getElementById('iva_porcentaje').value) || 0;
            let tipoCambio = parseFloat(document.getElementById('tipo_cambio').value) || 1;
            if (moneda !== 'USD') tipoCambio = 1;

            // Calcular monto del IVA
            const ivaMonto = gravado * (ivaPorcentaje / 100);
            document.getElementById('iva_monto').value = ivaMonto.toFixed(2);

            // Calcular subtotal
            const subtotal = gravado + ivaMonto;
            document.getElementById('subtotal').value = subtotal.toFixed(2);

            // Calcular importe total
            let importeTotal = subtotal;
            if (moneda === 'USD') {
                importeTotal = subtotal * tipoCambio;
            }
            document.getElementById('importe_total').value = importeTotal.toFixed(2);
        }

        // Función para calcular el total en la modal de edición
        function calcularTotalEdit() {
            const gravado = parseFloat(document.getElementById('edit_gravado').value) || 0;
            const ivaPorcentaje = parseFloat(document.getElementById('edit_iva_porcentaje').value) || 0;
            let tipoCambio = parseFloat(document.getElementById('edit_tipo_cambio').value);
            const moneda = document.getElementById('edit_moneda').value;
            if (isNaN(tipoCambio) || moneda !== 'USD') tipoCambio = 1;

            // Calcular monto del IVA
            const ivaMonto = gravado * (ivaPorcentaje / 100);
            document.getElementById('edit_iva_monto').value = ivaMonto.toFixed(2);

            // Calcular subtotal en USD o ARS
            const subtotal = gravado + ivaMonto;
            document.getElementById('edit_subtotal').value = subtotal.toFixed(2);

            // Calcular importe total en ARS
            const importeTotal = subtotal * tipoCambio;
            document.getElementById('edit_importe_total').value = importeTotal.toFixed(2);
        }

        // Inicializar eventos
        document.getElementById('moneda').addEventListener('change', onMonedaChange);
        document.getElementById('tipo_cambio').addEventListener('input', calcularTotal);
    </script>
@endsection
