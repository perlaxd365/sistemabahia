@extends('layout')
@section('title', 'Tramas AgA')

@section('view', Route::current()->getName())
@section('icon', 'file-text')
@section('date')
@section('content')

    @livewire('trama.aga')

@endsection
