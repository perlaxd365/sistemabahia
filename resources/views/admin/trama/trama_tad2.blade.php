@extends('layout')
@section('title', 'Tramas tad2')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.trama-tad2')

@endsection
