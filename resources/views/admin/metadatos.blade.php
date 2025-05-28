@extends('layouts.dashboard')
@section('title', 'Metadatos')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Metadatos</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['seccion', 'keyword', 'descripcion']" :data="$meta" updateRoute="metadatos.update" />
        </div>
    </div>
@endsection
