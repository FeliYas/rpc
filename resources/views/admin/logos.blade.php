@extends('layouts.dashboard')
@section('title', 'Logos')

@section('content')
    <div class="group relative h-full">
        <div class="py-3 text-xl text-gray-700">
            <h1>Logos</h1>
        </div>
        <!-- Línea expandible -->
        <hr class="border-t-[3px] border-main-color transition-all duration-500 ease-in-out line-width opacity-70 rounded">
        <div>
            <x-datatable-modal :columns="['path', 'seccion']" :data="$logos" updateRoute="logos.update" recomendacion="150x60"/>
        </div>
    </div>

@endsection
