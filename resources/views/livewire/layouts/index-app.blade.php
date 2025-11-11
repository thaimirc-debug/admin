<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{asset('css/styles.css')}}?tme={{base64_encode(now())}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <title>@stack('title', 'Admin Panel')</title>
    <meta name="description" content="@stack('description', 'ระบบจัดการเว็บไซต์')">
    <meta name="keywords" content="@stack('keywords','OKmini, Admin Panel')">
    @stack('styles')
    </head>
    <body>
        @stack('navbar')
        <main>
            <div class="container bg-white mb-3 shadow">
                @stack('ads')
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 p-3 bg-white">
                        {{ $slot ?? '' }}
                    </div>
                    <div class="col-lg-3 p-3 bg-white">
                        @stack('sidebar')
                    </div>
                </div>
            </div>
            <div class="footer pb-3">
                <p class="text-center my-4">
                    <span class="p-2 px-3 rounded-4 shadow bg-white small">
                        จัดทำโดย <strong>PS Home Care Thailand</strong> &copy;2025
                    </span>
                </p>
            </div>
        </main>
        @stack('scripts')
    </body>
</html>
