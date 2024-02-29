<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Fahim Anzam Dip" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    @include('includes.main-css')
</head>

<body class="c-app">
    @can('access_user_management')
        @include('layouts.sidebar')
    @endcan
    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed"  style="@if(auth()->user()->name == 'thungan') background-color: #0e873f; @endif">
            @include('layouts.header')

        </header>
        <div class="c-body">
            <main class="c-main" style="padding: 0!important;">
                @yield('content')
            </main>
        </div>
    </div>

    @include('includes.main-js')
</body>
</html>
