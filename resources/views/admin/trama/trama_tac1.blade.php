@extends('layout')
@section('title', 'Tramas tac1')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama-tac1')

@endsection
