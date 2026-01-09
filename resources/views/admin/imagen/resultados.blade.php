@extends('layout')

@section('title', 'Imagen Resultados')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')


   @livewire('imagen.resultados', ['id_orden' => $id_orden])
@endsection