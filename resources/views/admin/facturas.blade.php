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
                    <thead>
                        <tr class="bg-gray-200 border-b">
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"
                                    class="rounded border-gray-300 text-main-color focus:ring-main-color">
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Fecha</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Tipo</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Número</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Proveedor</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Moneda</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Importe Total</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100" id="facturaTableBody">
                        @forelse($facturas as $factura)
                            <tr class="hover:bg-gray-200 transition-colors duration-200 factura-row"
                                data-fecha="{{ $factura->fecha }}" data-proveedor="{{ $factura->proveedor->denominacion }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    <input type="checkbox" name="facturas[]" value="{{ $factura->id }}"
                                        class="factura-checkbox rounded border-gray-300 text-main-color focus:ring-main-color"
                                        onchange="updateSelectAllState()">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    {{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    {{ $factura->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    {{ $factura->puntoventa }}-{{ $factura->nrofactura }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    {{ $factura->proveedor->denominacion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    {{ $factura->moneda }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 font-semibold">
                                    ${{ number_format($factura->importe_total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="openEditModal({{ json_encode($factura) }})"
                                            class="hover:bg-orange-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                                    stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                                    stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button onclick="openDeleteModal({{ $factura->id }})"
                                            class="text-red-600 hover:bg-red-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="hover:bg-gray-200 transition-colors duration-200" id="noDataRow">
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    No hay facturas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
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

    <!-- Modal de Editar -->
    <div id="editFacturaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('editFacturaModal')" id="editModalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-2xl z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="editFacturaContent">
            <form id="editFacturaForm" method="POST" class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                    <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Factura
                    </h2>
                </div>

                <!-- Formulario -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Fecha -->
                        <div class="mb-4">
                            <label for="edit_fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input type="date" id="edit_fecha" name="fecha"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>

                        <!-- Tipo de Factura -->
                        <div class="mb-4">
                            <label for="edit_tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                                Factura</label>
                            <select id="edit_tipo" name="tipo"
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
                            <label for="edit_puntoventa" class="block text-sm font-medium text-gray-700 mb-1">Punto de
                                Venta</label>
                            <input type="text" id="edit_puntoventa" name="puntoventa"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>

                        <!-- Número de Factura -->
                        <div class="mb-4">
                            <label for="edit_nrofactura" class="block text-sm font-medium text-gray-700 mb-1">Número de
                                Factura</label>
                            <input type="text" id="edit_nrofactura" name="nrofactura"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required>
                        </div>

                        <!-- Proveedor -->
                        <div class="mb-4">
                            <label for="edit_proveedor_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                            <select id="edit_proveedor_id" name="proveedor_id"
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
                            <label for="edit_moneda" class="block text-sm font-medium text-gray-700 mb-1">Moneda</label>
                            <select id="edit_moneda" name="moneda"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required onchange="onMonedaChangeEdit()">
                                <option value="ARS" selected>PESOS (ARS)</option>
                                <option value="USD">DÓLARES (USD)</option>
                            </select>
                        </div>

                        <!-- Tipo de Cambio (solo si es USD) -->
                        <div class="mb-4" id="edit_tipoCambioGroup" style="display:none;">
                            <label for="edit_tipo_cambio" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                                Cambio (ARS/USD)</label>
                            <input type="number" id="edit_tipo_cambio" name="tipo_cambio" step="0.0001"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Detalle de Factura</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Monto Gravado (en ARS o USD) -->
                        <div class="mb-4">
                            <label for="edit_gravado" class="block text-sm font-medium text-gray-700 mb-1">Monto Gravado
                                (USD)</label>
                            <input type="number" id="edit_gravado" name="gravado" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required oninput="calcularTotalEdit()">
                        </div>

                        <!-- Porcentaje de IVA -->
                        <div class="mb-4">
                            <label for="edit_iva_porcentaje"
                                class="block text-sm font-medium text-gray-700 mb-1">IVA</label>
                            <select id="edit_iva_porcentaje" name="iva_porcentaje"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                required onchange="calcularTotalEdit()">
                                <option value="">Seleccione un porcentaje</option>
                                <option value="21.00">21%</option>
                                <option value="10.50">10.5%</option>
                                <option value="27.00">27%</option>
                            </select>
                        </div>

                        <!-- Monto de IVA (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="edit_iva_monto" class="block text-sm font-medium text-gray-700 mb-1">Monto IVA
                                (USD)</label>
                            <input type="text" id="edit_iva_monto" name="iva_monto" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 text-right" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Subtotal en USD (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="edit_subtotal" class="block text-sm font-medium text-gray-700 mb-1">Subtotal
                                (USD)</label>
                            <input type="text" id="edit_subtotal" name="subtotal" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 text-right" readonly>
                        </div>

                        <!-- Total en ARS (calculado automáticamente) -->
                        <div class="mb-4">
                            <label for="edit_importe_total" class="block text-sm font-medium text-gray-700 mb-1">Importe
                                Total (ARS)</label>
                            <input type="text" id="edit_importe_total" name="importe_total" step="0.01"
                                class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 font-bold text-lg text-right"
                                readonly>
                        </div>
                    </div>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editFacturaModal')"
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
                        Actualizar Factura
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal de Eliminar -->
    <div id="deleteFacturaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('deleteFacturaModal')" id="deleteModalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="deleteFacturaContent">
            <form id="deleteFacturaForm" method="POST" class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                @csrf
                @method('DELETE')

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
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">¿Estás seguro de que querés eliminar
                        esta factura?</h2>
                    <p class="text-gray-600 text-center mb-6">Esta acción no se puede deshacer.</p>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-center gap-4">
                    <button type="button" onclick="closeModal('deleteFacturaModal')"
                        class="btn-secondary px-5 py-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-white px-5 py-2 rounded cursor-pointer hover:bg-red-700 transition-colors duration-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Resumen de Facturas -->
    <div id="resumenFacturaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('resumenFacturaModal')" id="resumenModalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-3xl z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="resumenFacturaContent">
            <div class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                <!-- Header -->
                <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                    <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 11H15M9 15H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Resumen de Facturas Seleccionadas
                    </h2>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <!-- Lista de facturas seleccionadas -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-700 mb-3">Facturas incluidas en el resumen:</h3>
                        <div class="bg-gray-50 rounded-lg p-4 max-h-80 overflow-y-auto">
                            <div id="listaFacturasResumen" class="space-y-2">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de totales -->
                    <div class="bg-white rounded-lg border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Concepto</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto ARS</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Gravado
                                        (Neto)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">$<span
                                            id="totalGravadoARS">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total IVA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">$<span
                                            id="totalIvaARS">0.00</span></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">TOTAL GENERAL
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                        $<span id="totalGeneralARS">0.00</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Información adicional -->
                    <div class="mt-4 text-sm text-gray-600">
                        <p><strong>Cantidad de facturas:</strong> <span id="cantidadFacturas">0</span></p>
                        <p><strong>Fecha de generación:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('resumenFacturaModal')"
                        class="btn-secondary px-4 py-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cerrar
                    </button>
                    <button type="button" onclick="exportarResumenPDF()"
                        class="btn-primary px-4 py-2 flex items-center gap-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 9V2H18V9M6 18H4C3.46957 18 2.96086 17.7893 2.58579 17.4142C2.21071 17.0391 2 16.5304 2 16V11C2 10.4696 2.21071 9.96086 2.58579 9.58579C2.96086 9.21071 3.46957 9 4 9H20C20.5304 9 21.0391 9.21071 21.4142 9.58579C21.7893 9.96086 22 10.4696 22 11V16C22 16.5304 21.7893 17.0391 21.4142 17.4142C21.0391 17.7893 20.5304 18 20 18H18M6 14H18V22H6V14Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Exportar Resumen a PDF
                    </button>
                </div>
            </div>
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
        document.getElementById('gravado').addEventListener('input', calcularTotal);
        document.getElementById('iva_porcentaje').addEventListener('change', calcularTotal);

        // Al abrir modal, setear default
        document.addEventListener('DOMContentLoaded', function() {
            onMonedaChange();
        });

        // Funciones para manejo de checkboxes
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.factura-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            updateMultipleButtonState();
        }

        function updateSelectAllState() {
            const checkboxes = document.querySelectorAll('.factura-checkbox');
            const selectAll = document.getElementById('selectAll');
            const checkedBoxes = document.querySelectorAll('.factura-checkbox:checked');

            selectAll.checked = checkboxes.length === checkedBoxes.length;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;

            updateMultipleButtonState();
        }

        function updateMultipleButtonState() {
            const checkedBoxes = document.querySelectorAll('.factura-checkbox:checked');
            const imprimirBtn = document.getElementById('imprimirMultiplesBtn');
            const resumenBtn = document.getElementById('verResumenBtn');

            if (checkedBoxes.length > 0) {
                // Activar ambos botones
                imprimirBtn.disabled = false;
                imprimirBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                imprimirBtn.classList.add('cursor-pointer');

                resumenBtn.disabled = false;
                resumenBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                resumenBtn.classList.add('cursor-pointer');
            } else {
                // Desactivar ambos botones
                imprimirBtn.disabled = true;
                imprimirBtn.classList.add('opacity-50', 'cursor-not-allowed');
                imprimirBtn.classList.remove('cursor-pointer');

                resumenBtn.disabled = true;
                resumenBtn.classList.add('opacity-50', 'cursor-not-allowed');
                resumenBtn.classList.remove('cursor-pointer');
            }
        }

        // Funciones de impresión
        function imprimirFactura(id) {
            const url = "{{ route('facturas.imprimir', ':id') }}".replace(':id', id);
            window.open(url, '_blank');
        }

        function imprimirMultiples() {
            const checkedBoxes = document.querySelectorAll('.factura-checkbox:checked');

            if (checkedBoxes.length === 0) {
                alert('Seleccione al menos una factura para imprimir');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('facturas.imprimir.multiples') }}';
            form.target = '_blank';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'facturas[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Función para abrir modal de edición
        function openEditModal(data) {
            const modal = document.getElementById('editFacturaModal');
            const form = document.getElementById('editFacturaForm');
            const modalContent = document.getElementById('editFacturaContent');

            // Configurar la acción del formulario
            form.action = `{{ url('/dashboard/facturas/update') }}/${data.id}`;

            // Rellenar los campos básicos
            document.getElementById('edit_fecha').value = data.fecha;
            document.getElementById('edit_tipo').value = data.tipo;
            document.getElementById('edit_puntoventa').value = data.puntoventa;
            document.getElementById('edit_nrofactura').value = data.nrofactura;
            document.getElementById('edit_proveedor_id').value = data.proveedor_id;
            document.getElementById('edit_tipo_cambio').value = data.tipo_cambio;

            // Rellenar los detalles (asumiendo que hay al menos un detalle)
            if (data.detalles && data.detalles.length > 0) {
                const detalle = data.detalles[0];
                document.getElementById('edit_gravado').value = detalle.gravado;
                document.getElementById('edit_iva_porcentaje').value = detalle.iva_porcentaje;
                document.getElementById('edit_iva_monto').value = detalle.iva_monto;
                document.getElementById('edit_subtotal').value = detalle.subtotal;
            }

            document.getElementById('edit_importe_total').value = data.importe_total;

            // Setear la moneda seleccionada previamente
            if (data.moneda) {
                document.getElementById('edit_moneda').value = data.moneda;
            } else {
                document.getElementById('edit_moneda').value = 'ARS';
            }
            // Actualizar la UI de moneda y totales
            onMonedaChangeEdit();

            // Mostrar la modal
            modal.classList.remove('hidden');

            // Aplicar animación
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Configurar eventos para cálculos
            document.getElementById('edit_tipo_cambio').addEventListener('input', calcularTotalEdit);
        }

        // Función para abrir modal de eliminación
        function openDeleteModal(id) {
            const modal = document.getElementById('deleteFacturaModal');
            const form = document.getElementById('deleteFacturaForm');
            const modalContent = document.getElementById('deleteFacturaContent');

            // Configurar la acción del formulario
            form.action = `{{ url('/dashboard/facturas/delete') }}/${id}`;

            // Mostrar la modal
            modal.classList.remove('hidden');

            // Aplicar animación
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Función para cerrar modales
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = modal.querySelector('.transform');

            // Aplicar animación de cierre
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            // Ocultar después de la animación
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Función para calcular totales en modal de edición
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

        // Funciones de filtrado
        function filtrarPorFecha() {
            const fechaDesde = document.getElementById('fechaDesde').value;
            const fechaHasta = document.getElementById('fechaHasta').value;
            const proveedor = document.getElementById('filtroProveedor').value;
            const rows = document.querySelectorAll('.factura-row');
            let contador = 0;

            rows.forEach(row => {
                const fechaFactura = row.getAttribute('data-fecha');
                const proveedorFactura = row.getAttribute('data-proveedor');
                let mostrar = true;

                // Filtro por fecha desde
                if (fechaDesde && fechaFactura < fechaDesde) {
                    mostrar = false;
                }

                // Filtro por fecha hasta
                if (fechaHasta && fechaFactura > fechaHasta) {
                    mostrar = false;
                }

                // Filtro por proveedor
                if (proveedor && proveedorFactura !== proveedor) {
                    mostrar = false;
                }

                if (mostrar) {
                    row.style.display = '';
                    contador++;
                } else {
                    row.style.display = 'none';
                    // Desmarcar checkbox si está oculto
                    const checkbox = row.querySelector('.factura-checkbox');
                    if (checkbox) {
                        checkbox.checked = false;
                    }
                }
            });

            // Actualizar contador
            document.getElementById('contadorFacturas').textContent = contador;

            // Actualizar estado del botón de seleccionar todos
            updateSelectAllState();
            updateMultipleButtonState();

            // Mostrar/ocultar fila de "no datos"
            const noDataRow = document.getElementById('noDataRow');
            if (noDataRow) {
                noDataRow.style.display = contador === 0 ? '' : 'none';
            }
        }

        function limpiarFiltros() {
            document.getElementById('fechaDesde').value = '';
            document.getElementById('fechaHasta').value = '';
            document.getElementById('filtroProveedor').value = '';

            // Mostrar todas las filas
            const rows = document.querySelectorAll('.factura-row');
            rows.forEach(row => {
                row.style.display = '';
            });

            // Actualizar contador
            document.getElementById('contadorFacturas').textContent = rows.length;

            // Ocultar fila de "no datos" si existe
            const noDataRow = document.getElementById('noDataRow');
            if (noDataRow && rows.length > 0) {
                noDataRow.style.display = 'none';
            }

            // Actualizar estados
            updateSelectAllState();
            updateMultipleButtonState();
        }

        // Actualizar función de toggleSelectAll para considerar solo filas visibles
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.factura-checkbox');

            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('.factura-row');
                if (row && row.style.display !== 'none') {
                    checkbox.checked = selectAll.checked;
                }
            });

            updateMultipleButtonState();
        }

        // Actualizar función updateSelectAllState para considerar solo filas visibles
        function updateSelectAllState() {
            const checkboxes = document.querySelectorAll('.factura-checkbox');
            const selectAll = document.getElementById('selectAll');
            const visibleCheckboxes = Array.from(checkboxes).filter(checkbox => {
                const row = checkbox.closest('.factura-row');
                return row && row.style.display !== 'none';
            });

            const checkedVisibleBoxes = visibleCheckboxes.filter(checkbox => checkbox.checked);

            selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedVisibleBoxes.length;
            selectAll.indeterminate = checkedVisibleBoxes.length > 0 && checkedVisibleBoxes.length < visibleCheckboxes
                .length;

            updateMultipleButtonState();
        }

        // Función para mostrar el resumen de facturas
        function mostrarResumenFacturas() {
            const checkedBoxes = document.querySelectorAll('.factura-checkbox:checked');

            if (checkedBoxes.length === 0) {
                alert('Seleccione al menos una factura para ver el resumen');
                return;
            }

            let totalGravadoARS = 0;
            let totalIvaARS = 0;
            let totalGeneralARS = 0;
            let facturasInfo = [];

            checkedBoxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const facturaId = checkbox.value;
                const facturaData = @json($facturas).find(f => f.id == facturaId);

                if (facturaData && facturaData.detalles && facturaData.detalles.length > 0) {
                    const detalle = facturaData.detalles[0];
                    const tipoCambio = parseFloat(facturaData.tipo_cambio) || 1;
                    const moneda = facturaData.moneda;
                    let gravadoARS = 0;
                    let ivaARS = 0;
                    let totalARS = 0;

                    if (moneda === 'USD') {
                        gravadoARS = parseFloat(detalle.gravado) * tipoCambio;
                        ivaARS = parseFloat(detalle.iva_monto) * tipoCambio;
                        totalARS = parseFloat(facturaData.importe_total);
                    } else {
                        gravadoARS = parseFloat(detalle.gravado);
                        ivaARS = parseFloat(detalle.iva_monto);
                        totalARS = parseFloat(facturaData.importe_total);
                    }

                    totalGravadoARS += gravadoARS;
                    totalIvaARS += ivaARS;
                    totalGeneralARS += totalARS;

                    facturasInfo.push({
                        tipo: facturaData.tipo,
                        numero: `${facturaData.puntoventa}-${facturaData.nrofactura}`,
                        fecha: facturaData.fecha,
                        proveedor: facturaData.proveedor.denominacion,
                        gravado: parseFloat(detalle.gravado),
                        iva: parseFloat(detalle.iva_monto),
                        total: parseFloat(facturaData.importe_total),
                        moneda: facturaData.moneda,
                        tipoCambio: tipoCambio
                    });
                }
            });

            // Llenar la modal con los datos SOLO EN ARS
            document.getElementById('totalGravadoARS').textContent = totalGravadoARS.toFixed(2);
            document.getElementById('totalIvaARS').textContent = totalIvaARS.toFixed(2);
            document.getElementById('totalGeneralARS').textContent = totalGeneralARS.toFixed(2);
            document.getElementById('cantidadFacturas').textContent = facturasInfo.length;

            // Mostrar gravado, iva y total individual de cada factura (ajustado para mostrar el total en la moneda original)
            const listaFacturas = document.getElementById('listaFacturasResumen');
            listaFacturas.innerHTML = facturasInfo.map(factura => {
                let totalOriginal = 0;
                if (factura.moneda === 'USD') {
                    totalOriginal = factura.gravado + factura.iva;
                } else {
                    totalOriginal = factura.total;
                }
                return `<div class="flex flex-col md:flex-row md:justify-between items-start md:items-center p-2 bg-white rounded border">
                    <div>
                        <span class="font-medium">${factura.tipo} ${factura.numero}</span>
                        <div class="text-xs text-gray-500">
                            ${new Date(factura.fecha).toLocaleDateString('es-AR')}
                            ${factura.moneda === 'USD' ? ' - TC: $' + parseFloat(factura.tipoCambio).toFixed(4) : ''}
                        </div>
                    </div>
                    <div class="flex flex-row gap-4 mt-2 md:mt-0">
                        <div class="text-xs text-gray-700">
                            Gravado:
                            <span class="font-semibold">
                                $${factura.gravado.toFixed(2)}
                                <span class="text-green-600 font-bold ml-1">${factura.moneda}</span>
                            </span>
                        </div>
                        <div class="text-xs text-gray-700">
                            IVA:
                            <span class="font-semibold">
                                $${factura.iva.toFixed(2)}
                                <span class="text-green-600 font-bold ml-1">${factura.moneda}</span>
                            </span>
                        </div>
                        <div class="text-xs text-gray-700">
                            Total:
                            <span class="font-semibold">
                                $${totalOriginal.toFixed(2)}
                                <span class="text-green-600 font-bold ml-1">${factura.moneda}</span>
                            </span>
                        </div>
                    </div>
                </div>`
            }).join('');

            // Abrir la modal
            const modal = document.getElementById('resumenFacturaModal');
            const modalContent = document.getElementById('resumenFacturaContent');

            modal.classList.remove('hidden');

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Función para exportar el resumen a PDF
        function exportarResumenPDF() {
            const checkedBoxes = document.querySelectorAll('.factura-checkbox:checked');

            if (checkedBoxes.length === 0) {
                alert('No hay facturas seleccionadas');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('facturas.resumen.pdf') }}';
            form.target = '_blank';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'facturas[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
@endsection
