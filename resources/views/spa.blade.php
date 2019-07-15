@extends('layouts.plain')

@section('body')
	@include('misc.loader')
	<navbar></navbar>
	<router-view v-cloak></router-view>
@endsection
