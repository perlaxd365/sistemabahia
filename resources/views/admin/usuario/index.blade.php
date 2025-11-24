@extends('layout')

@section('title', 'Usuarios')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('admin.usuario')

@endsection