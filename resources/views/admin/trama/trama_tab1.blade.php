@extends('layout')
@section('title', 'Tramas tab1')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama-tab1')

@endsection
