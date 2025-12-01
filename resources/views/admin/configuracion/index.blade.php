@extends('layout')

@section('title', 'Configuración')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('configuracion.configuracion-index')

@endsection