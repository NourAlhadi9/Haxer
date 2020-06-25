<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            #main-wrapper {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .full-height {
                flex: 1;
                z-index: 1;
                float: left;
            }
            .footer {
                width: 100%;
                color: white;
                text-align: center;
            }

            #particles-js canvas {
                display: block;
                vertical-align: bottom;
                -webkit-transform: scale(1);
                -ms-transform: scale(1);
                transform: scale(1);
                opacity: 1;
                -webkit-transition: opacity .8s ease, -webkit-transform 1.4s ease;
                transition: opacity .8s ease, transform 1.4s ease
            }

            #particles-js {
                width: 100%;
                height: 100%;
                position: fixed;
                z-index: -10;
                top: 0;
                left: 0
            }
        </style>
        @yield('css')
    </head>
    <body style="background-color: #212529;">
        <div id="particles-js"></div>

        <div id="main-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark lead">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="{{ route('home') }}">H4x3R CTF</a>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link {{ (strpos(Route::currentRouteName(), 'home') == 0) ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link " href="#">Link</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                    
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('myteams.index') }}">My Teams</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('logout') }}">Logout</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('login') }}">Login</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="{{ route('register') }}">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif    
                    </ul>
                    
                    </div>
                </div>
            </nav>
            
            <div class="flex-center position-ref full-height">
                
                <div class="container mt-5">
                    @yield('content')
                </div>
            </div>

            

            <!-- Footer -->
            <footer class="footer font-small bg-dark text-white">
                <div class="footer-copyright text-center pt-3 h5">
                    Follow me: 
                    <a href="https://fb.com/nouralhadi.mahmoud.3" target="_blank"><i class="fa fa-lg fa-facebook-square ml-2 text-white"></i></a> 
                    <a href="https://github.com/NourAlhadi" target="_blank"><i class="fa fa-lg fa-github ml-2 text-white"></i></a> 
                    <a href="https://linkedin.com/in/NourAlhadi" target="_blank"><i class="fa fa-lg fa-linkedin ml-2 text-white"></i></a> 
                    <a href="https://instagram.com/nouralhadi9" target="_blank"><i class="fa fa-lg fa-instagram ml-2 text-white"></i></a> 
                    
                </div>
                
                <!-- Copyright -->
                <div class="footer-copyright text-center py-3">Made with &hearts; by
                    <a href="https://fb.com/nouralhadi.mahmoud.3" target="_blank"> Nour Alhadi Mahmoud</a>
                </div>
                <!-- Copyright -->

            </footer>
            <!-- Footer -->
        </div>
        
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="{{ asset('js/app.js') }}" type="text/javascript" defer></script>
        <script src="{{ asset('js/particles.js') }}"></script>
        <script src="{{ asset('js/particles-app.js') }}"></script>
        <script>

            window.onload = function () {
                @if($errors->any())
                    Swal.fire({
                        title: 'Error!',
                        text: '{{$errors->first()}}',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                @endif
            };

            particlesJS.load('particles-js', "{{ asset('particles.json')}}", function() {
                console.log('callback - particles.js config loaded');
            });
        </script>
        @yield('js')
    </body>
</html>
