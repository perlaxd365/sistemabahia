@extends('layout')

@section('title', 'Citas')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('cita.cita')

@endsection
