@extends('layout')

@section('title', 'Registro de Personal')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('admin.usuario')

@endsection