{{-- @extends('errors::minimal') --}}
@extends('errors::illustrated-layout')

@section('title', __('Forbidden'))
@section('image')
<img src="{{asset('assets/img/errors/403.jpg')}}" style="max-width: 100%;">
@endsection
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
