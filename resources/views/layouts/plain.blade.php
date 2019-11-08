<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title') | {{ config('app.name') }}</title>

	<!-- Fonts -->
	<link href="{{ mix('/css/app.css') }}" rel="stylesheet">


    @stack('head')
</head>
<body>
<div id="app" :class="`theme-${theme}`">
	@yield('body')

</div>

<script src="{{ mix('/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
