@extends('layouts.plain')

@section('title','Loading')

@push('head')
    @include('misc.pwaMeta')
    @include('misc.loader-style')
@endpush

@section('body')
	@include('misc.loader')
@endsection
