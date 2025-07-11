@extends('layouts.dashboard')

@section('title', 'Editar Liquidación')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Editar Liquidación</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div class="py-4">
            <!-- Header con información del empleado y botón volver -->
            <div class="flex justify-between items-center mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 flex-1 mr-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Empleado</p>
                            <p class="font-semibold text-gray-800">{{ $liquidacion->empleado->apellido }},
                                {{ $liquidacion->empleado->nombre }}</p>
                            <p class="text-sm text-gray-600 mt-2">Período</p>
                            <p class="font-medium text-gray-700">
                                {{ Carbon\Carbon::parse($liquidacion->fecha_inicio)->format('d/m/Y') }} -
                                {{ Carbon\Carbon::parse($liquidacion->fecha_fin)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tipo de Liquidación</p>
                            @if ($liquidacion->empleado->en_blanco)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Quincenal (En Blanco)
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Semanal (En Negro)
                                </span>
                            @endif
                            <p class="text-sm text-gray-600 mt-2">Valor Hora Base</p>
                            <p class="font-medium text-gray-700">${{ number_format($liquidacion->valor_hora_base, 2) }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('liquidaciones.dashboard') }}" class="btn-secondary flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('liquidaciones.update', $liquidacion) }}" id="editLiquidacionForm">
                @csrf
                @method('PUT')

                <!-- Tabla de días laborales con diseño minimalista -->
                <div class="bg-white rounded-lg shadow-sm mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Detalle de Días Laborales</h3>
                        <p class="text-sm text-gray-600">Edita las horas, extras, valor por hora y premios para cada día</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-200 border-b">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Día</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Fecha</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Horas</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Extras</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Total H.</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Valor Hora</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Premio</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-100" id="diasTableBody">
                                @foreach ($liquidacion->detalles as $detalle)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            {{ ucfirst($detalle->dia_semana) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ Carbon\Carbon::parse($detalle->fecha)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">
                                            <input type="number"
                                                class="w-20 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm horas-input"
                                                name="detalles[{{ $detalle->id }}][horas_trabajadas]"
                                                value="{{ $detalle->horas_trabajadas }}" min="0" max="24"
                                                step="0.5">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number"
                                                class="w-20 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm extras-input"
                                                name="detalles[{{ $detalle->id }}][horas_extra]"
                                                value="{{ $detalle->horas_extra }}" min="0" max="24"
                                                step="0.5">
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 total-horas-dia">
                                            {{ number_format($detalle->horas_trabajadas + $detalle->horas_extra, 2) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number"
                                                class="w-24 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm valor-hora-input"
                                                name="detalles[{{ $detalle->id }}][valor_hora]"
                                                value="{{ $detalle->valor_hora }}" min="0" step="0.01">
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-green-600 total-dia">
                                            ${{ number_format($detalle->total_dia, 2) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number"
                                                class="w-24 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm premio-input"
                                                name="detalles[{{ $detalle->id }}][premio_dia]"
                                                value="{{ $detalle->premio_dia }}" min="0" step="0.01">
                                        </td>
                                        <td class="px-4 py-3">
                                            <select
                                                class="w-32 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm"
                                                name="detalles[{{ $detalle->id }}][estado]">
                                                <option value="presente"
                                                    {{ $detalle->estado == 'presente' ? 'selected' : '' }}>Presente
                                                </option>
                                                <option value="ausente"
                                                    {{ $detalle->estado == 'ausente' ? 'selected' : '' }}>Ausente</option>
                                                <option value="feriado"
                                                    {{ $detalle->estado == 'feriado' ? 'selected' : '' }}>Feriado</option>
                                                <option value="vacaciones"
                                                    {{ $detalle->estado == 'vacaciones' ? 'selected' : '' }}>Vacaciones
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-300 border-t-2 border-gray-400">
                                    <td colspan="2" class="px-4 py-3 text-sm font-bold text-gray-900">TOTALES:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900" id="totalHoras">
                                        {{ number_format($liquidacion->total_horas_trabajadas, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900" id="totalExtras">
                                        {{ number_format($liquidacion->total_horas_extra, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900" id="totalHorasTotal">
                                        {{ number_format($liquidacion->total_horas_trabajadas + $liquidacion->total_horas_extra, 2) }}
                                    </td>
                                    <td class="px-4 py-3"></td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600" id="totalMonto">
                                        ${{ number_format($liquidacion->subtotal_base + $liquidacion->subtotal_extra, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-blue-600" id="totalPremios">
                                        ${{ number_format($liquidacion->total_premios, 2) }}</td>
                                    <td class="px-4 py-3"></td>
                                </tr>
                                <tr class="bg-green-50 border-t border-green-200">
                                    <td colspan="6" class="px-4 py-4 text-base font-bold text-green-800">TOTAL NETO A
                                        PAGAR:</td>
                                    <td colspan="3" class="px-4 py-4 text-xl font-bold text-green-600" id="totalNeto">
                                        ${{ number_format($liquidacion->total_neto, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Resumen con tarjetas modernas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Horas Trabajadas</p>
                                <p class="text-2xl font-bold text-blue-600" id="resumenHoras">
                                    {{ number_format($liquidacion->total_horas_trabajadas, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Horas Extra</p>
                                <p class="text-2xl font-bold text-yellow-600" id="resumenExtras">
                                    {{ number_format($liquidacion->total_horas_extra, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Premios</p>
                                <p class="text-2xl font-bold text-purple-600" id="resumenPremios">
                                    ${{ number_format($liquidacion->total_premios, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-sm p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-100">Total Neto</p>
                                <p class="text-3xl font-bold text-white" id="resumenTotal">
                                    ${{ number_format($liquidacion->total_neto, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center bg-white rounded-lg shadow-sm p-4">
                    <a href="{{ route('liquidaciones.dashboard') }}" class="btn-secondary flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19 21H5C4.44772 21 4 20.5523 4 20V4C4 3.44772 4.44772 3 5 3H16L20 7V20C20 20.5523 19.5523 21 19 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Actualizar Liquidación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll(
                '.horas-input, .extras-input, .valor-hora-input, .premio-input');
            inputs.forEach(input => {
                input.addEventListener('input', calcularTotales);
            });

            function calcularTotales() {
                let totalHoras = 0;
                let totalExtras = 0;
                let totalMonto = 0;
                let totalPremios = 0;

                document.querySelectorAll('#diasTableBody tr').forEach(row => {
                    const horas = parseFloat(row.querySelector('.horas-input').value) || 0;
                    const extras = parseFloat(row.querySelector('.extras-input').value) || 0;
                    const valorHora = parseFloat(row.querySelector('.valor-hora-input').value) || 0;
                    const premio = parseFloat(row.querySelector('.premio-input').value) || 0;

                    const totalHorasDia = horas + extras;
                    const montoNormal = horas * valorHora;
                    const montoExtras = extras * valorHora * 1.5;
                    const totalDia = montoNormal + montoExtras + premio;

                    row.querySelector('.total-horas-dia').textContent = totalHorasDia.toFixed(2);
                    row.querySelector('.total-dia').textContent = `$${totalDia.toFixed(2)}`;

                    totalHoras += horas;
                    totalExtras += extras;
                    totalMonto += montoNormal + montoExtras;
                    totalPremios += premio;
                });

                const totalNeto = totalMonto + totalPremios;

                // Actualizar tabla footer
                document.getElementById('totalHoras').textContent = totalHoras.toFixed(2);
                document.getElementById('totalExtras').textContent = totalExtras.toFixed(2);
                document.getElementById('totalHorasTotal').textContent = (totalHoras + totalExtras).toFixed(2);
                document.getElementById('totalMonto').textContent = `$${totalMonto.toFixed(2)}`;
                document.getElementById('totalPremios').textContent = `$${totalPremios.toFixed(2)}`;
                document.getElementById('totalNeto').textContent = `$${totalNeto.toFixed(2)}`;

                // Actualizar tarjetas de resumen
                document.getElementById('resumenHoras').textContent = totalHoras.toFixed(2);
                document.getElementById('resumenExtras').textContent = totalExtras.toFixed(2);
                document.getElementById('resumenPremios').textContent = `$${totalPremios.toFixed(2)}`;
                document.getElementById('resumenTotal').textContent = `$${totalNeto.toFixed(2)}`;
            }
        });
    </script>
@endsection
