@extends('layout')

@section('title', 'Perfil')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('perfil.perfil')

@endsection