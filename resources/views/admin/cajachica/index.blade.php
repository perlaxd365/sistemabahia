@extends('layout')

@section('title', 'Caja Chica')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('caja.caja-chica')

@endsection