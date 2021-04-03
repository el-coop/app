@extends('layouts.plain')

@section('title','Loading')

@push('head')
    @include('misc.pwaMeta')
    @include('misc.loader-style')
@endpush

@section('body')
	@include('misc.loader')
	<navbar v-if="$store.getters['auth/loggedIn']"></navbar>
    <div class="app-content" v-if="! loader">
        <router-view v-cloak></router-view>
    </div>
@endsection
