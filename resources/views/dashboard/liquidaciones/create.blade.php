@extends('layouts.dashboard')
@section('title', 'Nueva Liquidación')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Nueva Liquidación</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">

        <div class="py-4">
            <!-- Header con botón volver -->
            <div class="flex justify-between items-center mb-6">
                <div></div>
                <button onclick="window.location.href='{{ route('liquidaciones.dashboard') }}'"
                    class="btn-secondary flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Volver
                </button>
            </div>

            <!-- Formulario principal -->
            <form method="POST" action="{{ route('liquidaciones.store') }}" id="liquidacionForm"
                class="bg-white rounded-lg shadow-2xl overflow-hidden">
                @csrf

                <!-- Header del formulario -->
                <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                    <h2 class="text-lg font-semibold text-white">Configuración de Período</h2>
                </div>

                <!-- Selección de empleado y período -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Empleado *</label>
                            <select name="empleado_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50"
                                required id="empleadoSelect">
                                <option value="">Seleccione un empleado</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" data-valor-hora="{{ $empleado->valor_hora }}"
                                        data-cantidad-horas="{{ $empleado->cantidad_horas }}"
                                        data-en-blanco="{{ $empleado->en_blanco ? 'true' : 'false' }}"
                                        {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->apellido }}, {{ $empleado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Año *</label>
                            <select name="ano"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50"
                                required id="anoSelect">
                                @for ($i = 2020; $i <= 2030; $i++)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mes *</label>
                            <select name="mes"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50"
                                required id="mesSelect">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Período *</label>
                            <select name="semana"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50"
                                required id="semanaSelect">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-center mb-6">
                        <button type="button" class="btn-primary flex items-center gap-2" id="cargarDias">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"
                                    stroke="currentColor" stroke-width="2" />
                                <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor"
                                    stroke-width="2" />
                                <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor"
                                    stroke-width="2" />
                                <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                            Cargar Días del Período
                        </button>
                    </div>

                    <!-- Información del período -->
                    <div id="infoPeriodo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <strong class="text-blue-800">Información del Período</strong>
                        </div>
                        <p class="text-blue-700"><strong>Período seleccionado:</strong> <span id="fechasPeriodo"></span></p>
                        <p class="text-blue-700"><strong>Tipo de liquidación:</strong> <span id="tipoLiquidacion"></span>
                        </p>
                    </div>

                    <!-- Tabla de días laborales -->
                    <div id="diasContainer" class="hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Detalle de Días Laborales</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-200 border-b">
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Día</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Fecha</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Horas</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Extras</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Total Horas
                                        </th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Valor Hora</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Total</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Premio</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="diasTableBody" class="bg-white">
                                    <!-- Los días se cargarán aquí dinámicamente -->
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr class="border-t-2 border-gray-300">
                                        <td colspan="2" class="py-3 px-4 font-bold text-gray-800">Totales:</td>
                                        <td class="py-3 px-4 font-bold text-gray-800" id="totalHoras">0</td>
                                        <td class="py-3 px-4 font-bold text-gray-800" id="totalExtras">0</td>
                                        <td class="py-3 px-4 font-bold text-gray-800" id="totalHorasTotal">0</td>
                                        <td class="py-3 px-4"></td>
                                        <td class="py-3 px-4 font-bold text-green-600" id="totalMonto">$0.00</td>
                                        <td class="py-3 px-4 font-bold text-blue-600" id="totalPremios">$0.00</td>
                                        <td class="py-3 px-4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="window.location.href='{{ route('liquidaciones.dashboard') }}'"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary" id="btnGuardar" disabled>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor"
                                stroke-width="2" />
                            <polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="2" />
                            <polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="2" />
                        </svg>
                        Guardar Liquidación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const empleadoSelect = document.getElementById('empleadoSelect');
            const cargarDiasBtn = document.getElementById('cargarDias');
            const diasContainer = document.getElementById('diasContainer');
            const infoPeriodo = document.getElementById('infoPeriodo');
            const btnGuardar = document.getElementById('btnGuardar');
            const semanaSelect = document.getElementById('semanaSelect');

            empleadoSelect.addEventListener('change', function() {
                const option = this.selectedOptions[0];
                if (option && option.dataset.enBlanco === 'true') {
                    semanaSelect.innerHTML = `
                    <option value="1">Primera Quincena</option>
                    <option value="3">Segunda Quincena</option>
                `;
                } else {
                    semanaSelect.innerHTML = `
                    <option value="1">Semana 1</option>
                    <option value="2">Semana 2</option>
                    <option value="3">Semana 3</option>
                    <option value="4">Semana 4</option>
                `;
                }
            });

            cargarDiasBtn.addEventListener('click', function() {
                const empleadoId = empleadoSelect.value;
                const ano = document.getElementById('anoSelect').value;
                const mes = document.getElementById('mesSelect').value;
                const semana = semanaSelect.value;

                if (!empleadoId || !ano || !mes || !semana) {
                    alert('Por favor complete todos los campos del período');
                    return;
                }

                fetch(
                        `/api/liquidaciones/dias-laborales?empleado_id=${empleadoId}&ano=${ano}&mes=${mes}&semana=${semana}`)
                    .then(response => response.json())
                    .then(data => {
                        cargarDiasEnTabla(data);
                        diasContainer.classList.remove('hidden');
                        btnGuardar.disabled = false;
                        mostrarInfoPeriodo(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cargar los días laborales');
                    });
            });

            function mostrarInfoPeriodo(dias) {
                if (dias.length > 0) {
                    const fechaInicio = dias[0].fecha_formateada;
                    const fechaFin = dias[dias.length - 1].fecha_formateada;
                    const empleadoOption = empleadoSelect.selectedOptions[0];
                    const tipoLiquidacion = empleadoOption.dataset.enBlanco === 'true' ? 'Quincenal (En Blanco)' :
                        'Semanal (En Negro)';

                    document.getElementById('fechasPeriodo').textContent = `${fechaInicio} - ${fechaFin}`;
                    document.getElementById('tipoLiquidacion').textContent = tipoLiquidacion;
                    infoPeriodo.classList.remove('hidden');
                }
            }

            function cargarDiasEnTabla(dias) {
                const tbody = document.getElementById('diasTableBody');
                tbody.innerHTML = '';

                dias.forEach((dia, index) => {
                    const row = document.createElement('tr');
                    row.className = 'border-b border-gray-200 hover:bg-gray-50';
                    row.innerHTML = `
                    <td class="py-3 px-4 font-medium text-gray-900">${dia.dia_nombre}</td>
                    <td class="py-3 px-4 text-gray-600">
                        ${dia.fecha_formateada}
                        <input type="hidden" name="detalles[${index}][fecha]" value="${dia.fecha}">
                    </td>
                    <td class="py-3 px-4">
                        <input type="number" 
                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm horas-input" 
                               name="detalles[${index}][horas_trabajadas]" 
                               value="${dia.cantidad_horas}" 
                               min="0" 
                               max="24" 
                               step="0.5">
                    </td>
                    <td class="py-3 px-4">
                        <input type="number" 
                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm extras-input" 
                               name="detalles[${index}][horas_extra]" 
                               value="0" 
                               min="0" 
                               max="24" 
                               step="0.5">
                    </td>
                    <td class="py-3 px-4 text-gray-600 total-horas-dia">0</td>
                    <td class="py-3 px-4">
                        <input type="number" 
                               class="w-24 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm valor-hora-input" 
                               name="detalles[${index}][valor_hora]" 
                               value="${dia.valor_hora}" 
                               min="0" 
                               step="0.01">
                    </td>
                    <td class="py-3 px-4 font-medium text-green-600 total-dia">$0.00</td>
                    <td class="py-3 px-4">
                        <input type="number" 
                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm premio-input" 
                               name="detalles[${index}][premio_dia]" 
                               value="0" 
                               min="0" 
                               step="0.01">
                    </td>
                    <td class="py-3 px-4">
                        <select class="w-28 rounded-md border-gray-300 shadow-sm focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-50 text-sm" name="detalles[${index}][estado]">
                            <option value="presente">Presente</option>
                            <option value="ausente">Ausente</option>
                            <option value="feriado">Feriado</option>
                            <option value="vacaciones">Vacaciones</option>
                        </select>
                    </td>
                `;
                    tbody.appendChild(row);
                });

                agregarEventListeners();
                calcularTotales();
            }

            function agregarEventListeners() {
                const inputs = document.querySelectorAll(
                    '.horas-input, .extras-input, .valor-hora-input, .premio-input');
                inputs.forEach(input => {
                    input.addEventListener('input', calcularTotales);
                });
            }

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

                document.getElementById('totalHoras').textContent = totalHoras.toFixed(2);
                document.getElementById('totalExtras').textContent = totalExtras.toFixed(2);
                document.getElementById('totalHorasTotal').textContent = (totalHoras + totalExtras).toFixed(2);
                document.getElementById('totalMonto').textContent = `$${totalMonto.toFixed(2)}`;
                document.getElementById('totalPremios').textContent = `$${totalPremios.toFixed(2)}`;
            }
        });
    </script>
@endsection
