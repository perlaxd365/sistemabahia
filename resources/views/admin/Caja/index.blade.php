@extends('layout')

@section('title', 'Caja')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('caja.caja-turno')

@endsection