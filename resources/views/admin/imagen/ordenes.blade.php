@extends('layout')

@section('title', 'Imagen')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('imagen.ordenes')

@endsection