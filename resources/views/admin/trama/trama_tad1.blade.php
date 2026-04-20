@extends('layout')
@section('title', 'Tramas tad1')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama-tad1')

@endsection
