@extends('layout')

@section('title', 'Ventas')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('reportes.ventas')

@endsection