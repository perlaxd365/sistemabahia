@extends('layout')

@section('title', 'Atención')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')
{{-- 

    <h3>Atención #{{ $atencion->id }}</h3>

    @include('atencion.navbar-tabs', ['id_atencion' => $id_atencion]) --}}
    @livewire('atencion.home', ['id_atencion' => $id_atencion])

@endsection
