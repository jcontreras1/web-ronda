@extends('layouts.app')

@section('assets')
    @vite(['resources/sass/app.scss', 'resources/js/rondas.js'])
@endsection

@section('inertia', 'true')

@section('content')
    @inertia('rondas-app')
@endsection
