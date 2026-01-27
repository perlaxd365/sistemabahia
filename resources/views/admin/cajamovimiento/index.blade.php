@extends('layout')

@section('title', 'Movimiento Caja')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('caja.caja-movimiento')

@endsection