@extends('layout')

@section('title', 'Inicio')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('index.index-action')

@endsection