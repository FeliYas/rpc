@props([
    'columns' => [],
    'data' => [],
    'categorias' => [],
    'deleteRoute',
    'updateRoute',
    'createRoute',
    'productoid',
    'descuentoid' => null,
    'recomendacion',
    'pedidoInfo',
])
<div class="py-4">
    <!-- Header con título y botón -->
    @if (isset($createRoute) && isset($descuentoid))
    <div class="flex justify-start items-center mb-6">
        <button class="btn-primary flex items-center gap-2" onclick="openProductSelectionModal('productSelectionModal')">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            Agregar Productos
        </button>
    </div>
    @elseif (isset($createRoute))
        <div class="flex justify-start items-center mb-6">
            <button class="btn-primary flex items-center gap-2" onclick="openModal('createModalWrapper')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Agregar
            </button>
        </div>
    @endif


    <!-- Tabla con diseño minimalista -->
    <div class="bg-white rounded-lg shadow-2xl max-w-screen overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200 border-b">
                    @foreach ($columns as $column)
                        @if ($column !== 'password')
                            @if ($column == 'path')
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                    Imagen
                                </th>
                            @elseif($column == 'categoria_id')
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                    Categoria
                                </th>
                            @elseif($column == 'producto_id')
                            @elseif($column == 'descripcion')
                            @elseif($column == 'cantidad_minima')
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                    Cantidad mínima
                                </th>
                                
                            @elseif($column == 'direfiscal')
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                    Direccion fiscal
                                </th>
                            @elseif($column == 'unidad')
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                    Unidad de venta
                                </th>
                            @else
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                                    {{ ucfirst($column) }}
                                </th>
                            @endif
                        @endif
                    @endforeach
                    <th class="px-6 py-4 text-center text-xs font-medium text-main-color uppercase tracking-wider">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-100">
                @if ($data->isEmpty())
                    <tr class="hover:bg-gray-200 transition-colors duration-200">
                        <td colspan="{{ count($columns) + 1 }}"
                            class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                            No hay datos disponibles
                        </td>
                    </tr>
                @else
                    @foreach ($data as $row)
                        <tr class="hover:bg-gray-200 transition-colors duration-200">
                            @foreach ($columns as $column)
                                @if ($column !== 'password')
                                    @if ($column == 'path')
                                        <td class="h-[100px] px-6 py-4 whitespace-nowrap flex justify-center">
                                            @if ($row->path)
                                                <img src="{{ asset('storage/' . $row->path) }}"
                                                    alt="Imagen del producto">
                                            @else
                                                <div class="flex items-center justify-center w-full h-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-12 w-12 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        @elseif ($column == 'Numero de pedido')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            {{ $row->getNumeroPedidoAttribute() }}
                                        </td>
                                    @elseif ($column == 'Email cliente')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            {{ $row->cliente ? $row->cliente->email : 'Sin email' }}
                                        </td>
                                    @elseif ($column == 'Metodo de entrega')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            {{ $row->getTipoEntregaAttribute() }}
                                        </td>
                                    @elseif ($column == 'mensaje')
                                        <td class="px-6 py-4 text-sm text-center text-gray-700">
                                            <div class="max-h-[100px] overflow-y-auto scrollbar-custom">
                                                {{ $row->mensaje ?? 'Sin mensaje' }}
                                            </div>
                                        </td>
                                    @elseif ($column == 'archivo')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            @if ($row->archivo)
                                                <a href="{{ asset('storage/' . $row->archivo) }}" target="_blank"
                                                    class="flex justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-8 w-8 text-red-600 hover:text-red-800 transition-colors"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8" />
                                                    </svg>
                                                </a>
                                            @else
                                                <div class="flex justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-8 w-8 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                    @elseif ($column == 'completado')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            <div class="flex justify-center gap-3">
                                                <span id="status-{{ $row->id }}" class="px-3 py-1 rounded-full {{ $row->completado ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                    {{ $row->getEstadoAttribute() }}
                                                </span>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer"
                                                        @if ($row->completado) checked @endif
                                                        onchange="toggleCompletado({{ $row->id }}, this.checked)">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0D8141]">
                                                    </div>
                                                </label>
                                            </div>
                                        </td>
                                    @elseif ($column == 'caracteristicas')
                                        <td class="px-6 py-4 text-sm text-left text-gray-700">
                                            <div class="flex flex-col items-center gap-2 text-center">
                                                <a href="{{ route('caracteristicas.dashboard', $row->id) }}"
                                                    class="text-gray-500 hover:text-[#0D8141] transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 hover:scale-110 transform transition duration-200"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    @elseif ($column == 'imagenes')
                                        <td class="px-6 py-4 text-sm text-left text-gray-700">
                                            <div class="flex flex-col items-center gap-2 text-center">
                                                <a href="{{ route('imagenes.dashboard', $row->id) }}"
                                                    class="text-gray-500 hover:text-[#0D8141] transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 hover:scale-110 transform transition duration-200"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    @elseif ($column == 'productos participantes')
                                        <td class="px-6 py-4 text-sm text-left text-gray-700">
                                            <div class="flex flex-col items-center gap-2 text-center">
                                                <a href="{{ route('participantes.dashboard', $row->id) }}"
                                                    class="text-gray-500 hover:text-[#0D8141] transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 hover:scale-110 transform transition duration-200"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    @elseif($column == 'titulo')
                                        <td class="px-6 py-4 text-sm text-left text-gray-700 ">
                                            <div class="h-[80px] overflow-auto scrollbar-custom">
                                                {{ $row->titulo }}
                                            </div>
                                        </td>
                                    @elseif ($column == 'descripcion')
                                        
                                    @elseif ($column == 'producto_id')

                                    @elseif ($column == 'descuento')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            {{ $row->descuento }}%
                                        </td>
                                    @elseif ($column == 'categoria_id')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            {{ $row->categoria ? $row->categoria->titulo : 'Sin categoría' }}
                                        </td>
                                    @elseif ($column == 'ficha')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            @if ($row->ficha)
                                                <a href="{{ asset('storage/' . $row->ficha) }}" target="_blank"
                                                    class="flex justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-8 w-8 text-red-600 hover:text-red-800 transition-colors"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8" />
                                                    </svg>
                                                </a>
                                            @else
                                                <div class="flex justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-8 w-8 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                    @elseif ($column == 'lista')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            <a href="{{ asset('storage/' . $row->lista) }}" target="_blank"
                                                class="flex justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-8 w-8 text-red-600 hover:text-red-800 transition-colors"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8" />
                                                </svg>
                                                {{ basename($row->lista) }}</a>
                                        </td>
                                    @elseif ($column === 'destacado')
                                        <td>
                                            <div class="flex justify-center">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer"
                                                        @if ($row->destacado) checked @endif
                                                        onchange="toggleDestacado({{ $row->id }}, this.checked)">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0D8141]">
                                                    </div>
                                                </label>
                                            </div>
                                        </td>
                                    @elseif ($column === 'autorizado')
                                        <td>
                                            <div class="flex justify-center">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer"
                                                        @if ($row->autorizado) checked @endif
                                                        onchange="toggleAutorizado({{ $row->id }}, this.checked)">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0D8141]">
                                                    </div>
                                                </label>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                            {{ ucfirst($row[$column]) }}
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <!-- Botón para ver productos -->
                                    @if (isset($pedidoInfo))
                                        <button onclick="openProductsModal('{{ $row->id }}')"
                                            class="hover:bg-green-100 p-2 rounded-full transition-all duration-200 cursor-pointer text-blue-600">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                    stroke="#0D8141" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z"
                                                    stroke="#0D8141" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    @if (isset($updateRoute))
                                        <button onclick="openEditModal({{ json_encode($row) }})"
                                            class=" hover:bg-orange-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
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
                                    @endif
                                    @if (isset($deleteRoute))
                                        <button onclick="openDeleteModal({{ $row->id }})"
                                            class="text-red-600 hover:bg-red-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
<!-- Modal para mostrar productos del pedido -->
<div id="viewProductsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <!-- Overlay con animación -->
    <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
        onclick="closeProductsModal('viewProductsModal')" id="productViewModalOverlay"></div>

    <div class="relative w-full max-w-2xl z-50 transition-all duration-300 transform scale-95 opacity-0" id="viewProductsContent">
        <div class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
            <!-- Header -->
            <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 6L12 2L8 6M19 9H5C3.89543 9 3 9.89543 3 11V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V11C21 9.89543 20.1046 9 19 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Productos del Pedido
                </h2>
            </div>

            <div class="py-4 px-6">
                <!-- Lista de productos del pedido -->
                <div class="overflow-y-auto max-h-80">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unidad de Venta
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cantidad
                                </th>
                            </tr>
                        </thead>
                        <tbody id="orderProductsList" class="bg-white divide-y divide-gray-200">
                            <!-- Los productos se cargarán mediante JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer con botones -->
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="closeProductsModal('viewProductsModal')"
                    class="btn-secondary px-4 py-2 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para abrir la modal de productos
    function openProductsModal(pedidoId) {
        const modal = document.getElementById('viewProductsModal');
        const modalContent = document.getElementById('viewProductsContent');
        const overlay = document.getElementById('productViewModalOverlay');
        
        // Mostrar la modal
        modal.classList.remove('hidden');
        
        // Aplicar animación
        setTimeout(() => {
            overlay.classList.add('opacity-60');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Cargar los productos del pedido
        loadOrderProducts(pedidoId);
    }
    
    // Función para cerrar la modal de productos
    function closeProductsModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContent = document.getElementById('viewProductsContent');
        const overlay = document.getElementById('productViewModalOverlay');
        
        // Aplicar animación de cierre
        overlay.classList.remove('opacity-60');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        // Ocultar la modal después de la animación
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Función para cargar los productos del pedido
    function loadOrderProducts(pedidoId) {
        // Mostrar indicador de carga
        document.getElementById('orderProductsList').innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-main-color inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Cargando productos...
                </td>
            </tr>
        `;
        
        // Realizar la solicitud AJAX para obtener los productos del pedido
        fetch(`/api/pedidos/${pedidoId}/productos`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayOrderProducts(data.productos);
                } else {
                    document.getElementById('orderProductsList').innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                ${data.message || 'No se encontraron productos para este pedido.'}
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error al cargar los productos:', error);
                document.getElementById('orderProductsList').innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-red-500">
                            Error al cargar los productos. Intente nuevamente.
                        </td>
                    </tr>
                `;
            });
    }
    
    // Función para mostrar los productos en la tabla
    function displayOrderProducts(productos) {
        const productsList = document.getElementById('orderProductsList');
        productsList.innerHTML = '';
        
        if (!productos || productos.length === 0) {
            productsList.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        No hay productos en este pedido.
                    </td>
                </tr>
            `;
            return;
        }
        
        // Parsear los productos si están en formato JSON string
        let productosArray = productos;
        if (typeof productos === 'string') {
            try {
                productosArray = JSON.parse(productos);
            } catch (e) {
                console.error('Error al parsear productos:', e);
                return;
            }
        }
        
        // Si es un objeto y no un array, convertirlo a array
        if (!Array.isArray(productosArray)) {
            productosArray = [productosArray];
        }
        
        // Iterar y mostrar los productos
        productosArray.forEach(producto => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${producto.codigo || 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${producto.titulo || 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${producto.unidad_venta || 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${producto.cantidad || 'N/A'}
                </td>
            `;
            productsList.appendChild(row);
        });
    }
</script>

<!-- Modal para selección de productos -->
@if (isset($descuentoid))
    <div id="productSelectionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeProductSelectionModal('productSelectionModal')" id="productModalOverlay"></div>

        <div class="relative w-full max-w-3xl z-50 transition-all duration-300 transform scale-95 opacity-0" id="productSelectionContent">
            <div class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
                <!-- Header -->
                <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                    <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Seleccionar Productos para Descuento
                    </h2>
                </div>

                <div class="py-4 px-6">
                    <!-- Search Box -->
                    <div class="mb-4">
                        <input type="text" id="productSearch" placeholder="Buscar productos..."
                            class="w-full border border-gray-300 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                    </div>

                    <!-- Lista de productos disponibles -->
                    <div class="max-h-60 overflow-y-auto mb-4 border rounded">
                        <div id="availableProducts" class="p-2">
                            <!-- Los productos se cargarán mediante AJAX -->
                            <div class="text-center py-4">Cargando productos...</div>
                        </div>
                    </div>

                    <!-- Productos seleccionados -->
                    <div class="mb-4">
                        <h3 class="block text-sm font-medium text-gray-700 mb-1">Productos Seleccionados (<span id="selectedCount">0</span>)</h3>
                        <div id="selectedProducts" class="max-h-40 overflow-y-auto border rounded p-2">
                            <p id="emptyCart" class="text-gray-500 text-center py-2">No hay productos seleccionados</p>
                        </div>
                    </div>

                    <!-- Form para enviar los productos -->
                    <form id="addProductsForm" method="POST" action="{{ route('participantes.store') }}">
                        @csrf
                        <input type="hidden" name="descuento_id" value="{{ $descuentoid }}">
                        <div id="selectedProductsInputs"></div>
                    </form>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeProductSelectionModal('productSelectionModal')"
                        class="btn-secondary px-4 py-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                    <button type="button" id="saveProductsBtn" disabled onclick="prepareFormSubmission(event)" 
                        class="btn-primary px-4 py-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Solo inicializar si estamos en la vista de productos de descuento
        if (document.getElementById('productSelectionModal')) {
            initProductSelection();
        }
    });

    let allProducts = [];
    let selectedProducts = [];

    function initProductSelection() {
        // Cargar productos cuando se abra el modal
        document.querySelector('[onclick="openProductSelectionModal(\'productSelectionModal\')"]').addEventListener('click', loadProducts);
        
        // Configurar la búsqueda
        document.getElementById('productSearch').addEventListener('input', filterProducts);
    }

    function loadProducts() {
        // Fetch para obtener todos los productos que no están ya asociados al descuento
        fetch(`/api/productos/disponibles/${{{ $descuentoid }}}`)
            .then(response => response.json())
            .then(data => {
                allProducts = data;
                renderAvailableProducts();
            })
            .catch(error => {
                document.getElementById('availableProducts').innerHTML = `
                    <div class="text-red-500 text-center py-2">Error al cargar productos</div>`;
                console.error('Error:', error);
            });
    }

    function renderAvailableProducts() {
        const container = document.getElementById('availableProducts');
        
        if (allProducts.length === 0) {
            container.innerHTML = `<p class="text-center py-2">No hay productos disponibles</p>`;
            return;
        }
        
        const productsHtml = allProducts.map(product => `
            <div class="product-item flex justify-between items-center p-2 hover:bg-gray-100 cursor-pointer" 
                 onclick="toggleProductSelection(${product.id}, '${product.titulo}')">
                <div>${product.titulo}</div>
                <div id="check-product-${product.id}" class="hidden text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </div>
            </div>
        `).join('');
        
        container.innerHTML = productsHtml;
    }

    function filterProducts() {
        const searchTerm = document.getElementById('productSearch').value.toLowerCase();
        
        const filteredProducts = allProducts.filter(product => 
            product.titulo.toLowerCase().includes(searchTerm)
        );
        
        // Renderizar productos filtrados pero mantener estados seleccionados
        const container = document.getElementById('availableProducts');
        
        if (filteredProducts.length === 0) {
            container.innerHTML = `<p class="text-center py-2">No se encontraron productos</p>`;
            return;
        }
        
        const productsHtml = filteredProducts.map(product => {
            const isSelected = selectedProducts.some(p => p.id === product.id);
            return `
                <div class="product-item flex justify-between items-center p-2 hover:bg-gray-100 cursor-pointer" 
                     onclick="toggleProductSelection(${product.id}, '${product.titulo}')">
                    <div>${product.titulo}</div>
                    <div id="check-product-${product.id}" class="${isSelected ? '' : 'hidden'} text-green-500"></div>
                </div>
            `;
        }).join('');
        
        container.innerHTML = productsHtml;
    }

    function toggleProductSelection(productId, productName) {
        const index = selectedProducts.findIndex(p => p.id === productId);
        const checkIcon = document.getElementById(`check-product-${productId}`);
        
        if (index === -1) {
            // Agregar producto
            selectedProducts.push({ id: productId, nombre: productName });
            checkIcon.classList.remove('hidden');
        } else {
            // Quitar producto
            selectedProducts.splice(index, 1);
            checkIcon.classList.add('hidden');
        }
        
        updateSelectedProductsList();
    }

    function updateSelectedProductsList() {
        const container = document.getElementById('selectedProducts');
        const emptyCart = document.getElementById('emptyCart');
        const countElement = document.getElementById('selectedCount');
        const saveButton = document.getElementById('saveProductsBtn');
        
        countElement.textContent = selectedProducts.length;
        
        if (selectedProducts.length === 0) {
            emptyCart.classList.remove('hidden');
            container.querySelectorAll('.selected-product-item').forEach(el => el.remove());
            saveButton.disabled = true;
            return;
        }
        
        emptyCart.classList.add('hidden');
        saveButton.disabled = false;
        
        // Limpiar productos actuales
        container.querySelectorAll('.selected-product-item').forEach(el => el.remove());
        
        // Agregar productos seleccionados
        selectedProducts.forEach(product => {
            const productElement = document.createElement('div');
            productElement.className = 'selected-product-item flex justify-between items-center p-2 border-b last:border-b-0';
            productElement.innerHTML = `
                <div>${product.nombre}</div>
                <button type="button" class="text-red-500 cursor-pointer" onclick="removeSelectedProduct(${product.id})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
            `;
            container.appendChild(productElement);
        });
    }

    function removeSelectedProduct(productId) {
        const index = selectedProducts.findIndex(p => p.id === productId);
        if (index !== -1) {
            selectedProducts.splice(index, 1);
            
            // Actualizar estado visual en la lista de disponibles si está visible
            const checkIcon = document.getElementById(`check-product-${productId}`);
            if (checkIcon) {
                checkIcon.classList.add('hidden');
            }
            
            updateSelectedProductsList();
        }
    }

    function prepareFormSubmission(e) {
        e.preventDefault();
        
        // Limpiar inputs anteriores
        document.getElementById('selectedProductsInputs').innerHTML = '';
        
        // Crear inputs para cada producto seleccionado
        selectedProducts.forEach(product => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'producto_ids[]';
            input.value = product.id;
            document.getElementById('selectedProductsInputs').appendChild(input);
        });
        
        // Enviar el formulario
        document.getElementById('addProductsForm').submit();
    }

    // Funciones para el modal con nombres específicos
    function openProductSelectionModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContent = document.getElementById('productSelectionContent');
        
        modal.classList.remove('hidden');
        
        // Agregar animación después de un pequeño retraso
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeProductSelectionModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContent = document.getElementById('productSelectionContent');
        
        // Primero animación de salida
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        // Luego ocultar después de la animación
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

@if (isset($createRoute))
    <div id="createModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('createModalWrapper')" id="modalOverlay"></div>

        <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="createModal">
            <form action="{{ route($createRoute) }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-lg shadow-lg w-full max-h-screen overflow-y-auto">
                @csrf

                <!-- Header -->
                <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                    <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Crear
                    </h2>
                </div>
                <!-- Formulario -->
                <div class="p-6">
                    @foreach ($columns as $column)
                        @if ($column !== 'id')
                            <div class="mb-4">
                                @if ($column === 'categoria_id')
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                                        <select name="{{ $column }}"
                                            class="w-full border border-gray-300 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color appearance-none"
                                            required>
                                            <option value="">Seleccione una categoría</option>
                                            @isset($categorias)
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}">{{ $categoria->titulo }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                @elseif ($column === 'ficha')
                                    <label for="{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Ficha Técnica
                                        (PDF)
                                    </label>
                                    <div class="relative w-full">
                                        <label for="{{ $column }}" id="customFileLabelFicha"
                                            class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                            Seleccionar PDF (opcional)
                                        </label>
                                        <input type="file" id="{{ $column }}" name="{{ $column }}"
                                            class="hidden" accept=".pdf"
                                            onchange="updateFileNameLabel(this, 'customFileLabelFicha')">
                                    </div>
                                @elseif ($column === 'lista')
                                    <label for="{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Lista de Precios (PDF,
                                        XLSX, XLS)</label>
                                    </label>
                                    <div class="relative w-full">
                                        <label for="{{ $column }}" id="customFileLabelFicha"
                                            class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                            Seleccionar Lista
                                        </label>
                                        <input type="file" id="{{ $column }}" name="{{ $column }}"
                                            class="hidden" accept=".pdf, .xlsx, .xls"
                                            onchange="updateFileNameLabel(this, 'customFileLabelFicha')">
                                    </div>
                                @elseif ($column === 'path')
                                    <label for="{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                                    <div class="relative w-full">
                                        <label for="{{ $column }}" id="customFileLabel"
                                            class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                            Elija una imagen
                                        </label>
                                        <input type="file" id="{{ $column }}" name="{{ $column }}"
                                            class="hidden" onchange="createFileLabel(this)">
                                        <p class="text-xs text-gray-400 mt-1">Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM,
                                            OGG. Recomendacion: {{$recomendacion}} px </p>
                                    </div>
                                @elseif ($column === 'producto_id')
                                    <input type="hidden" name="{{ $column }}"
                                        value="{{ $productoid ?? '' }}">
                                @elseif ($column === 'cantidad_minima')
                                    <label for="cantidad_minima"
                                        class="block text-sm font-medium text-gray-700 mb-1">Cantidad Mínima</label>
                                    <input type="number" name="cantidad_minima" id="cantidad_minima"
                                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                        value="{{ old('cantidad_minima') }}" required>
                                @elseif ($column === 'descuento')
                                    <label for="descuento"
                                        class="block text-sm font-medium text-gray-700 mb-1">Descuento <span
                                            class="text-gray-500">(Sin el %. Por ej: 10)</span></label>
                                    <input type="number" name="descuento" id="descuento"
                                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                        value="{{ old('descuento') }}" required>
                                @elseif ($column === 'precio')
                                    <label for="precio"
                                        class="block text-sm font-medium text-gray-700 mb-1">Precio <span
                                            class="text-gray-500">(Sin comas, puntos o signos. Por ej: 10000)</span></label>
                                    <input type="number" name="precio" id="precio"
                                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                        value="{{ old('precio') }}" required>
                                @elseif ($column === 'caracteristicas')

                                @elseif ($column === 'productos participantes')

                                @elseif ($column === 'imagenes')

                                @elseif ($column === 'destacado')
                                @elseif ($column === 'adword')
                                @elseif ($column === 'role')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                                    <div class="relative">
                                        <select name="{{ $column }}" id="edit_{{ $column }}"
                                            class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color appearance-none">
                                            <option value="user" {{ old($column) == 'user' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="admin" {{ old($column) == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                            <option value="pedido" {{ old($column) == 'pedido' ? 'selected' : '' }}>
                                                Pedido
                                            </option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                @elseif ($column === 'password')
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña (Min 8 caracteres)</label>
                                    <div class="relative">
                                        <input id="passwordInput" name="{{ $column }}" type="password"
                                            class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            required>
                                        <button type="button" onclick="togglePasswordVisibility()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer">
                                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7A9.97 9.97 0 014.02 8.971m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        @if ($column == 'codigo')
                                            Código
                                        @elseif ($column == 'titulo')
                                            Título
                                        @elseif ($column == 'descripcion')
                                            Descripción
                                        @else
                                            {{ ucfirst($column) }}
                                        @endif
                                    </label>
                                    @if ($column == 'descripcion')
                                        <div class="custom-container">
                                            <textarea name="descripcion" class="summernote w-full" data-placeholder="Descripción..."></textarea>
                                        </div>
                                    @else
                                        <input name="{{ $column }}" type="text"
                                            class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            required>
                                    @endif
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModalWrapper')"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Crear
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

@if (isset($updateRoute))
    <!-- Modal de Editar -->
    <div id="editModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('editModalWrapper')" id="modalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-xl z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="editModal">
            <form id="editForm" method="POST" class="bg-white rounded-lg shadow-lg w-full max-h-screen overflow-y-auto "
                data-action-template="{{ route($updateRoute, '__ID__') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="bg-main-color bg-opacity-10 px-5 py-3 border-b border-main-color border-opacity-20">
                    <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </h2>
                </div>

                <!-- Formulario -->
                <div class="p-4">
                    @foreach ($columns as $column)
                        @if ($column !== 'id')
                            <div class="mb-4">
                                @if ($column === 'categoria_id')
                                    <div class="relative">
                                        <label for="edit_{{ $column }}"
                                            class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                                        <select name="{{ $column }}" id="edit_{{ $column }}"
                                            class="w-full border border-gray-300 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color appearance-none"
                                            required>
                                            <option value="">Seleccione una categoría</option>
                                            @isset($categorias)
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}">{{ $categoria->titulo }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                @elseif ($column === 'caracteristicas')

                                @elseif ($column === 'imagenes')

                                @elseif ($column === 'destacado')

                                @elseif ($column === 'productos participantes')

                                @elseif ($column === 'autorizado')

                                @elseif ($column === 'cuit')

                                @elseif ($column === 'ficha')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Ficha Técnica (PDF)
                                    </label>
                                    <div class="relative w-full">
                                        <label for="edit_{{ $column }}" id="edit_customFileLabelFicha"
                                            class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                            Seleccionar nuevo PDF (opcional)
                                        </label>
                                        <input type="file" id="edit_{{ $column }}"
                                            name="{{ $column }}" class="hidden" accept=".pdf"
                                            onchange="updateFileNameLabel(this, 'edit_customFileLabelFicha')">
                                    </div>
                                    <div class="mt-2" id="current_ficha_container">
                                        <p class="block text-sm text-gray-700">Ficha técnica actual: <span
                                                id="current_ficha_name">Ninguna</span></p>
                                    </div>
                                @elseif ($column === 'lista')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Lista de Precios (PDF,
                                        XLSX, XLS)</label>
                                    </label>
                                    <div class="relative w-full">
                                        <label for="edit_{{ $column }}" id="edit_customFileLabelFicha"
                                            class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                            Seleccionar nueva lista de precios
                                        </label>
                                        <input type="file" id="edit_{{ $column }}"
                                            name="{{ $column }}" class="hidden" accept=".pdf, .xlsx, .xls"
                                            onchange="updateFileNameLabel(this, 'edit_customFileLabelFicha')">
                                    </div>
                                @elseif ($column === 'role')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                                    <div class="relative">
                                        <select name="{{ $column }}" id="edit_{{ $column }}"
                                            class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color appearance-none">
                                            <option value="user" {{ old($column) == 'user' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="admin" {{ old($column) == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                            <option value="pedido" {{ old($column) == 'pedido' ? 'selected' : '' }}>
                                                Pedido
                                            </option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                @elseif ($column === 'rol')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                                    <div class="relative">
                                        <select name="{{ $column }}" id="edit_{{ $column }}"
                                            class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color appearance-none">
                                            <option value="cliente" {{ old($column) == 'cliente' ? 'selected' : '' }}>
                                                Cliente
                                            </option>
                                            <option value="vendedor"
                                                {{ old($column) == 'vendedor' ? 'selected' : '' }}>
                                                Vendedor
                                            </option>
                                            <option value="minorista"
                                                {{ old($column) == 'minorista' ? 'selected' : '' }}>
                                                Minorista
                                            </option>
                                            <option value="distribuidor"
                                                {{ old($column) == 'distribuidor' ? 'selected' : '' }}>
                                                Distribuidor
                                            </option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                @elseif($column === 'producto_id')
                                    <input type="hidden" id="edit_{{ $column }}" name="{{ $column }}"
                                        value="{{ $row->producto_id ?? ($productoid ?? '') }}">
                                @elseif ($column === 'password')
                                    <label for="edit_passwordInput"
                                        class="block text-sm font-medium text-gray-700 mb-1">Contraseña (Min 8 caracteres)</label>
                                    <div class="relative">
                                        <input id="edit_passwordInput" name="{{ $column }}" type="password"
                                            class="w-full border border-gray-300 px-4 py-2 rounded-md text-sm focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            placeholder="Dejar en blanco para mantener la misma contraseña">
                                        <button type="button" onclick="toggleEditPasswordVisibility()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer">
                                            <svg id="editEyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="editEyeOffIcon" xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7A9.97 9.97 0 014.02 8.971m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                @elseif ($column === 'path')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                                    <div class="relative w-full">
                                        <label for="edit_{{ $column }}" id="edit_customFileLabel"
                                            class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                            Elija una nueva imagen
                                        </label>
                                        <input type="file" id="edit_{{ $column }}"
                                            name="{{ $column }}" class="hidden"
                                            onchange="updateFileLabel(this)">
                                        <p class="text-xs text-gray-400 mt-1">Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM,
                                            OGG. Recomendacion: {{$recomendacion}} px </p>
                                    </div>

                                    <div class="mt-2">
                                        <p class="block text-sm text-gray-700">Imagen actual:</p>
                                        <img id="edit_imagePreview" src="" alt="Imagen actual"
                                            class="w-full h-40 object-contain border border-main-color rounded-md bg-gray-200 p-2">
                                    </div>
                                @elseif ($column === 'precio')
                                    <label for="precio"
                                        class="block text-sm font-medium text-gray-700 mb-1">Precio <span
                                            class="text-gray-500">(Sin comas, puntos o signos. Por ej: 10000)</span></label>
                                    <input type="number" name="precio" id="precio"
                                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                        value="{{ old('precio') }}" required>
                                @elseif ($column === 'descripcion')
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Descripción
                                    </label>
                                    <div class="custom-container">
                                        <textarea id="edit_descripcion" name="descripcion" class="summernote w-full" data-placeholder="Descripción..."></textarea>
                                    </div>
                                @else
                                    <label for="edit_{{ $column }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        @if ($column == 'codigo')
                                            Código
                                        @elseif ($column == 'titulo')
                                            Título
                                        @else
                                            {{ ucfirst($column) }}
                                        @endif
                                    </label>
                                    <input name="{{ $column }}" id="edit_{{ $column }}" type="text"
                                        class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModalWrapper')"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

@if (isset($deleteRoute))
    <div id="deleteModalWrapper" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay con animación -->
        <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm transition-opacity duration-300"
            onclick="closeModal('deleteModalWrapper')" id="deleteModalOverlay"></div>

        <!-- Modal con animación -->
        <div class="relative w-full max-w-md z-50 transition-all duration-300 transform scale-95 opacity-0"
            id="deleteModal">
            <form id="deleteForm" method="POST" class="bg-white rounded-lg shadow-lg w-full overflow-hidden"
                data-action-template="{{ route($deleteRoute, '__ID__') }}">
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
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">¿Estás seguro de que querés
                        eliminar
                        esta fila?</h2>
                    <p class="text-gray-600 text-center mb-6">Esta acción no se puede deshacer.</p>
                </div>

                <!-- Footer con botones -->
                <div class="px-6 py-4 bg-gray-50 flex justify-center gap-4">
                    <button type="button" onclick="closeModal('deleteModalWrapper')"
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
@endif

<script>
    function createFileLabel(input) {
        const label = document.getElementById('customFileLabel');
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = 'Elija una imagen';
        }
    }

    function updateFileLabel(input) {
        const label = document.getElementById('customFileLabel');
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = 'Elija una nueva imagen';
        }
    }

    // Nueva función para actualizar la etiqueta de cualquier archivo
    function updateFileNameLabel(input, labelId) {
        const label = document.getElementById(labelId);
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
        } else {
            if (labelId === 'customFileLabelFicha') {
                label.textContent = 'Seleccionar PDF (opcional)';
            } else {
                label.textContent = 'Elija un archivo';
            }
        }
    }

    function toggleEditPasswordVisibility() {
        const passwordInput = document.getElementById('edit_passwordInput');
        const eyeIcon = document.getElementById('editEyeIcon');
        const eyeOffIcon = document.getElementById('editEyeOffIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }

    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function openModal(id) {
        const wrapper = document.getElementById(id);
        wrapper.classList.remove('hidden');

        const modal = wrapper.querySelector('div[id$="Modal"]');
        if (modal) {
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
            }, 10); // delay para que la transición se dispare
        }
    }

    function closeModal(id) {
        const wrapper = document.getElementById(id);
        const modal = wrapper.querySelector('div[id$="Modal"]');
        if (modal) {
            modal.classList.add('opacity-0', 'scale-95');
            modal.classList.remove('opacity-100', 'scale-100');
        }
        setTimeout(() => {
            wrapper.classList.add('hidden');
        }, 300); // esperar que termine la transición
    }

    // Función unificada para abrir modal de edición
    function openEditModal(data) {
        const modal = document.getElementById('editModalWrapper');
        const form = document.getElementById('editForm');
        const actionTemplate = form.getAttribute('data-action-template');
        const imgPreview = document.getElementById('edit_imagePreview');

        // Actualizar la URL del formulario con el ID
        form.action = actionTemplate.replace('__ID__', data.id);

        // Rellenar los campos con los datos existentes
        Object.keys(data).forEach(key => {
            const input = document.getElementById(`edit_${key}`);

            if (input && key !== 'password' && key !== 'descripcion' && key !== 'ficha' && input.type !==
                'file') {
                if (key === 'categoria_id' && input.tagName === 'SELECT') {
                    // Para el select de categorías
                    Array.from(input.options).forEach(option => {
                        option.selected = option.value == data[key];
                    });
                } else {
                    // Para campos normales
                    input.value = typeof data[key] === 'string' ? data[key] : data[key];
                }
            }
        });

        // Actualizar el editor summernote si existe
        if (data.descripcion) {
            // Si estamos utilizando Summernote
            if ($.fn.summernote) {
                $('#edit_descripcion').summernote('code', data.descripcion);
            } else {
                // Fallback si summernote no está inicializado
                const descripcionInput = document.getElementById('edit_descripcion');
                if (descripcionInput) {
                    descripcionInput.value = data.descripcion;
                }
            }
        }

        // Manejar la ficha técnica si existe y es string válida
        if (typeof data.ficha === 'string' && data.ficha.trim() !== '') {
            const currentFichaName = document.getElementById('current_ficha_name');
            if (currentFichaName) {
                const fileName = data.ficha.split('/').pop();
                currentFichaName.textContent = fileName;
                document.getElementById('current_ficha_container').style.display = 'block';
            }
        } else {
            const fichaContainer = document.getElementById('current_ficha_container');
            if (fichaContainer) {
                fichaContainer.style.display = 'none';
            }
        }
        // Manejar la ficha técnica si existe y es string válida
        if (typeof data.lista === 'string' && data.lista.trim() !== '') {
            const currentFichaName = document.getElementById('current_lista_name');
            if (currentFichaName) {
                const fileName = data.lista.split('/').pop();
                currentFichaName.textContent = fileName;
                document.getElementById('current_lista_container').style.display = 'block';
            }
        } else {
            const listaContainer = document.getElementById('current_lista_container');
            if (listaContainer) {
                listaContainer.style.display = 'none';
            }
        }


        // Asegurarnos que el campo password esté vacío
        const passwordInput = document.getElementById('edit_passwordInput');
        if (passwordInput) {
            passwordInput.value = '';
        }

        // Actualizar imagen si hay 'path'
        if (imgPreview && data.path) {
            imgPreview.src = `/storage/${data.path}`;
        }

        // Abrir el modal usando la función existente
        openModal('editModalWrapper');
    }
    

    function toggleDestacado(id, isChecked) {
        fetch("{{ route('categorias.toggleDestacado') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id,
                    destacado: isChecked ? 1 : 0
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Destacado actualizado correctamente");
                } else {
                    console.error("Error al actualizar el destacado");
                }
            });
    }

    function toggleAutorizado(id, isChecked) {
        fetch("{{ route('clientes.toggleAutorizado') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id,
                    autorizado: isChecked ? 1 : 0
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Autorizado actualizado correctamente");
                } else {
                    console.error("Error al actualizar el autorizado");
                }
            });
    }
    function toggleCompletado(id, isChecked) {
        fetch("{{ route('pedidos.toggleCompletado') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id,
                completado: isChecked ? 1 : 0
            })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the text dynamically
                const statusSpan = document.querySelector(`#status-${id}`);
                if (statusSpan) {
                    statusSpan.textContent = isChecked ? 'Completado' : 'Pendiente';
                    statusSpan.className = isChecked 
                        ? 'px-3 py-1 rounded-full bg-green-100 text-green-800' 
                        : 'px-3 py-1 rounded-full bg-yellow-100 text-yellow-800';
                }
            } else {
                console.error("Error updating status:", data.message);
            }
        }).catch(error => {
            console.error("Error:", error);
        });
    }
</script>
{{-- Si $descuentoid está definido, se utiliza en la función openDeleteModal --}}
@if (isset($descuentoid))
<script>
    function openDeleteModal(id) {
        const form = document.getElementById('deleteForm');
        const actionTemplate = form.getAttribute('data-action-template');
        form.action = actionTemplate.replace('__ID__', id);
        
        // Crear campo oculto con el ID del descuento
        if (document.getElementById('hidden_descuento_id')) {
            document.getElementById('hidden_descuento_id').remove();
        }
        
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'descuento_id';
        hiddenInput.id = 'hidden_descuento_id';
        hiddenInput.value = {{ $descuentoid }};
        form.appendChild(hiddenInput);
        
        openModal('deleteModalWrapper');
    }
</script>
@else
    <script>
        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            const actionTemplate = form.getAttribute('data-action-template');
            form.action = actionTemplate.replace('__ID__', id);
            openModal('deleteModalWrapper');
        }
    </script> 
@endif
