@extends('errors::illustrated-layout')

@section('title', __('Service Unavailable'))
@section('image')
<img src="{{asset('assets/img/errors/503.jpg')}}" style="max-width: 100%;">
@endsection
@section('code', '503')
@section('message', __('Service Unavailable'))

