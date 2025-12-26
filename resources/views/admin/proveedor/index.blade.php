@extends('layout')

@section('title', 'Proveedor')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('proveedor.proveedor-action')

@endsection