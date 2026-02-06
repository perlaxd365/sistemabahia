@extends('layout')

@section('title', 'Ingresos')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('reportes.ingresos')

@endsection