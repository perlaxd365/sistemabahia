@extends('layout')
@if (auth()->user()->privilegio_cargo == 1)

    @section('title', 'Registro de Personal')
@else
    @section('title', 'Registro de Paciente')

@endif

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('admin.usuario')

@endsection
