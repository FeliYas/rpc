@extends('layouts.dashboard')
@section('title', 'Liquidaciones')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Liquidaciones</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div class="py-4">
            <!-- Header con título y botón -->
            <div class="flex justify-between items-center mb-6">
                <button class="btn-primary flex items-center gap-2"
                    onclick="window.location.href='{{ route('liquidaciones.create') }}'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Nueva Liquidación
                </button>

                <div class="text-sm text-gray-600">
                    Mostrando <span id="contadorLiquidaciones">{{ count($liquidaciones) }}</span> liquidaciones
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" id="filtrosForm">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label for="empleado_id" class="text-sm font-medium text-gray-700">Empleado:</label>
                            <select name="empleado_id" id="empleado_id"
                                class="rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm">
                                <option value="">Todos los empleados</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}"
                                        {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->apellido }}, {{ $empleado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="fecha_desde" class="text-sm font-medium text-gray-700">Desde:</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="fecha_hasta" class="text-sm font-medium text-gray-700">Hasta:</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm">
                        </div>
                        <button type="submit"
                            class="bg-main-color text-white px-4 py-2 rounded-md hover:bg-main-color-dark transition-colors duration-300 text-sm flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                                <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2" />
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('liquidaciones.dashboard') }}"
                            class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-white border border-red-700 hover:text-red-700 cursor-pointer transition-colors duration-300 text-sm flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla con diseño minimalista -->
            <div class="bg-white rounded-lg shadow-2xl max-w-screen overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200 border-b">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Empleado</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Período</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Tipo</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Horas Trabajadas</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Horas Extra</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Total Neto</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Fecha Creación</th>
                            <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100" id="liquidacionTableBody">
                        @forelse($liquidaciones as $liquidacion)
                            <tr
                                class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150 liquidacion-row">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $liquidacion->empleado->apellido }},
                                        {{ $liquidacion->empleado->nombre }}</div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ Carbon\Carbon::parse($liquidacion->fecha_inicio)->format('d/m/Y') }} -
                                    {{ Carbon\Carbon::parse($liquidacion->fecha_fin)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($liquidacion->empleado->en_blanco)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Quincenal
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Semanal
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ number_format($liquidacion->total_horas_trabajadas, 2) }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ number_format($liquidacion->total_horas_extra, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="font-bold text-green-600">${{ number_format($liquidacion->total_neto, 2) }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $liquidacion->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center space-x-2">
                                        <button
                                            onclick="window.location.href='{{ route('liquidaciones.edit', $liquidacion) }}'"
                                            class="text-yellow-600 hover:text-yellow-800 transition-colors duration-150"
                                            title="Editar">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button onclick="openDeleteModal({{ $liquidacion->id }})"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-150"
                                            title="Eliminar">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="noDataRow">
                                <td colspan="8" class="py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium mb-2">No hay liquidaciones registradas</p>
                                        <p class="text-sm text-gray-400 mb-4">Comienza creando tu primera liquidación</p>
                                        <button onclick="window.location.href='{{ route('liquidaciones.create') }}'"
                                            class="btn-primary">
                                            Nueva Liquidación
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $liquidaciones->links() }}
            </div>

            <!-- Modal de Eliminar -->
            <div id="deleteLiquidacionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
                <!-- Overlay con animación -->
                <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
                    onclick="closeModal('deleteLiquidacionModal')" id="deleteModalOverlay"></div>

                <!-- Modal con animación -->
                <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
                    id="deleteLiquidacionContent">
                    <form id="deleteLiquidacionForm" method="POST"
                        class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                        @csrf
                        @method('DELETE')

                        <!-- Header con icono de advertencia -->
                        <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-center">
                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6 text-center">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmar Eliminación</h3>
                            <p class="text-sm text-gray-600">¿Está seguro de que desea eliminar esta liquidación? Esta
                                acción no se puede deshacer.</p>
                        </div>

                        <!-- Footer con botones -->
                        <div class="px-6 py-4 bg-gray-50 flex justify-center gap-4">
                            <button type="button" onclick="closeModal('deleteLiquidacionModal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                                Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // Función para abrir modal de eliminación
                function openDeleteModal(id) {
                    const modal = document.getElementById('deleteLiquidacionModal');
                    const form = document.getElementById('deleteLiquidacionForm');
                    const modalContent = document.getElementById('deleteLiquidacionContent');

                    // Configurar la acción del formulario
                    form.action = `{{ url('/dashboard/liquidaciones') }}/${id}`;

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

                // Auto submit del formulario de filtros cuando cambia el empleado
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('empleado_id').addEventListener('change', function() {
                        document.getElementById('filtrosForm').submit();
                    });
                });
            </script>
        </div>
    </div>
@endsection
