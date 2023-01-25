<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title') | {{ config('app.name') }}</title>

    {{
        Vite::useBuildDirectory('/')
            ->useManifestFilename('assets.json')
            ->withEntryPoints(['resources/js/app.js','resources/sass/app.scss'])
    }}


    @stack('head')
</head>
<body>
<div id="app">
	@yield('body')

</div>

@stack('scripts')
</body>
</html>
