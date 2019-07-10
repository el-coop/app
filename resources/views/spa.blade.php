@extends('layouts.plain')

@section('body')
	@include('misc.loader')
	<router-view v-cloak></router-view>
@endsection
