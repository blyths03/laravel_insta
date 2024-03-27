<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}| @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> {{-- use asset for public-folder --}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <h1 class="h5 mb-0">{{ config('app.name') }}</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- serch bar here later --}}
                    
                    @auth
                        {{-- This will not show up in the admin --}}
                        @if (!request()->is('admin/*'))
                            <ul class="navbar-nav ms-auto">
                                <form action="{{ route('search') }}" style="width: 300px;">
                                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search...">
                                </form>
                            </ul>
                        @endif
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        {{--@guest : if you are not logged in  --}}
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
                        @else {{-- If you are logged in, below shows --}}

                        {{-- home --}}
                        <li class="nav-item" title="Home">
                            <a href="{{ route('index') }}" class="nav-link"><i class="fa-solid fa-house text-dark icon-sm"></i></a>
                        </li>

                        {{-- create post --}}
                        <li class="nav-item" title="create post">
                            <a href="{{ route('post.create') }}" class="nav-link"><i class="fa-solid fa-circle-plus text-dark icon-sm"></i></a>
                        </li>

                        {{-- account --}}
                        <li class="nav-item dropdown">
                            <button id="account-dropdown" class="btn shadow-non nav-link" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->avatar }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-dark icon-sm"></i>
                                @endif
                            </button>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @can('admin')
                                    {{-- admin controls  --}}
                                    <a href="{{ route('admin.users') }}" class="dropdown-item">
                                        <i class="fa-solid fa-user-gear"></i> Admin
                                    </a>

                                    <hr class="dropdown-divider">
                                @endcan
                                {{-- profile --}}
                                <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                    <i class="fa-solid fa-circle-user"></i>Profile
                                </a>

                                {{-- logout --}}
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracker"></i>{{ __('Logout') }}
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

        <main class="py-5">

            <div class="container">

                <div class="row justify-content-center">

                    {{-- adin controls --}}
                    @if (request()->is('admin/*'))
                    {{-- if browser address starts from admin/ the admin control will be show. ex. admin/posts or admin/users --}}
                        <div class="col-3">
                            <div class="list-group">
                                <a href="{{ route('admin.users') }}" class="list-group-item {{ request()->is('admin/users') ? 'active' : '' }}">
                                    {{-- if you clicked it turns color blue as active in browser --}}
                                    <i class="fa-solid fa-users"></i> Users
                                </a>

                                <a href="{{ route('admin.posts') }}" class="list-group-item {{ request()->is('admin/posts') ? 'active' : '' }}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>

                                <a href="{{ route('admin.categories') }}" class="list-group-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-9">
                        @yield('content')
                    </div>
                    
                </div>

            </div>
 
        </main>
    </div>
</body>
</html>
