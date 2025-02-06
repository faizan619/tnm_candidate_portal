<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'T&M Services Consulting Private Limited') }}</title>
    <link rel="icon" type="image/png" href="{{asset('img/logo.jpg')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand p-0" href="{{ url('/') }}">
                    <img src="{{asset('img/logo.jpg')}}" alt="T&M Logo" width="120">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('current_opening')}}">Current Opening</a></li>
                        <!-- <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Current Openings
                          </a>
                          <ul class="dropdown-menu bg-white" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{url('contractuals')}}">Contractual</a></li>
                            <li><a class="dropdown-item" href="#">Permanent</a></li>
                            
                          </ul>
                        </li> -->
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                                
                            @endif
                        @else
                           
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa-solid fa-circle-user" style="font-size: 40px; float: left;"></i>
                                    <span style="line-height: 40px; margin-left: 5px;">
                                    {{ucwords(Auth::user()->name) }}
                                    </span>
                                </a>

                                <div class="dropdown-menu bg-white dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{route('myprofile')}}">
                                        My profile
                                    </a>
                                    <a class="dropdown-item" href="{{route('myapplication')}}">
                                        My applications
                                    </a>
                                    <a class="dropdown-item" href="{{route('mydocument')}}">
                                        My documents
                                    </a>
                                    <a class="dropdown-item" href="{{route('apply_for_job')}}">
                                        Apply for a job
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="">Need help? Raise a ticket</a>
                                    <a class="dropdown-item" href="{{ route('password.change') }}">
                                        Change Password
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container p-0">
                @yield('content')
            </div>
        </main>
        <footer class="p-4 text-center">
            <p class="mb-0">Copy Right &copy; 2024, All rights reserved by <a href="tnmhr.com" class="text-danger" style="text-decoration: none;">tnmhr.com</a></p>
        </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script src="//cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @yield('script')
    <script>
        let table = new DataTable('.DataTable');
         flatpickr('.date', {
            dateFormat: "d/m/Y", // Format to display in the input
            allowInput: true, // Allow manual input
            onClose: function(selectedDates, dateStr, instance) {
                // Set the value in the input to the formatted date
                //document.getElementById('date_of_birth').value = dateStr;
            }
        });
    </script>
</body>
</html>
