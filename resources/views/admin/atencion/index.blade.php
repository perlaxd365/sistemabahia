@extends('layout')

@section('title', 'Atención')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('atencion.atencion-index')

@endsection