@extends('layout')

@section('title', 'Laboratorio Resultados')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')


   @livewire('laboratorio.resultados', ['id_orden' => $id_orden])
@endsection