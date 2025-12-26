@extends('layout')

@section('title', 'Farmacia')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('farmacia.farmacia-index')

@endsection