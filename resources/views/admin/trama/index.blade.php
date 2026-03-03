@extends('layout')
@section('title', 'Tramas')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama')

@endsection
