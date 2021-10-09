<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>@yield('title')</title>

    {{-- bootstrp css --}} 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    {{-- fontawesome --}}
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">

    @yield('extra_css')
   
</head>
<body>
    <div id="app">
        <div class="header-menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4 text-center">
                        </div>
                        <div class="col-4 text-center">
                            <h3>@yield('title')</h3>
                        </div>
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fas fa-bells"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>
        </div>

        <div class="bottom-menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fas fa-qrcode"></i>
                                <p>Scan</p>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="{{ route('profile') }}">
                                <i class="fas fa-user"></i>
                                <p>Account</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- bootstrap js --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    {{-- Sweet Alert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function () {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if(token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN' : token.content,
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                    }
                });
            };

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            @if (Session('create')) 
                Toast.fire({
                    icon: 'success',
                    title: "{{ Session('create') }}"
                });
            @endif

            @if (Session('update')) 
                Toast.fire({
                    icon: 'success',
                    title: "{{ Session('update') }}"
                });
            @endif
        });
    </script>
    
    @yield('scripts')
</body>
</html>
