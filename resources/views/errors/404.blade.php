{{-- @extends('errors::minimal') --}}
@extends('errors::illustrated-layout')

@section('title', __('Not Found'))
@section('image')
<img src="{{asset('assets/img/errors/404.jpg')}}" style="max-width: 100%;">
@endsection
@section('code', '404')
@section('message', __('Not Found'))
