{{-- @extends('errors::minimal') --}}
@extends('errors::illustrated-layout')

@section('title', __('Server Error'))
@section('image')
<img src="{{asset('assets/img/errors/500.jpg')}}" style="max-width: 100%;">
@endsection
@section('code', '500')
@section('message', __('Server Error'))
