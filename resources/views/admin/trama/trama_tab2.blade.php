@extends('layout')
@section('title', 'Tramas tab2')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama-tab2')

@endsection
