@extends('layout')

@section('title', 'Laboratorio')
@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('laboratorio.ordenes')

@endsection