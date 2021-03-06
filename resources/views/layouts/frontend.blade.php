<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title> {{ config('app.name')}} </title>

  <!-- Bootstrap core CSS -->
  <link href=" {{ asset('css/app.css') }}" rel = "stylesheet">

  <!-- Custom styles for this template -->
  <link href=" {{ asset('css/theme.css') }}" rel = "stylesheet">

</head>

<body>

  <!-- Navigation -->
    <div id="app">
            <nav class="navbar navbar-expand-lg navbar-dark bg-info fixed-top">
                    <div class="container">
                      <a class="navbar-brand" href="/">{{ config('app.name')}}</a>
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav ml-auto">
                          <li class="nav-item {{ request()->routeIs('welcome') ? 'bg-light' :''}}">
                          <a class="nav-link" href="{{ route('welcome') }}">Home
                              <span class="sr-only">(current)</span>
                            </a>
                          </li>
                          <li class="nav-item {{ request()->routeIs('about') ? 'bg-light' :''}}">
                          <a class="nav-link" href="{{ route('about') }}">About</a>
                          </li>
                          @auth
                          <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index')}}">ตะกร้าสินค้า
                                <span class="badge badge-success">
                                    {{ App\Cart::where('user_id',auth()->user()->id)->sum('qty')}}
                                </span>

                            </a>
                          </li>
                          @endauth
                          <li class="nav-item {{ request()->routeIs('contact.index') ? 'bg-light' :''}}">
                          <a class="nav-link" href="{{ route('contact.index')}}">Contact</a>
                          </li>
                          <!-- Authentication Links -->
                          @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        DashBoard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest



                        </ul>
                      </div>
                    </div>
                  </nav>

                  <!-- Page Content -->
                  <div class="container">
                      @yield('content')
                  </div>
                  <!-- /.container -->
                @include('partials.footer')


    </div>

  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('js/app.js') }}"></script>

  @yield('footerscript')
</body>

</html>
