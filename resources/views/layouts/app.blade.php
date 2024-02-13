<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="icon" href="{{ asset('img/logo-bm.png') }}" type="image/x-icon">
        <title>@yield('title') | {{ config('app.name') }}</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>

    <body>
        @include('layouts.header')

        <div id="app">
            @yield('content')
        </div>
    </body>

    @include('layouts.script')
</html>
