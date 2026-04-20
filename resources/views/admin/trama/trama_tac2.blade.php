@extends('layout')
@section('title', 'Tramas tac2')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama-tac2')

@endsection
