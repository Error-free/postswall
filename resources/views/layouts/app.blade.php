<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('media/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" media="screen">
    <meta charset="utf-8">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        <ul class="nav">
            <li class="{{ Request::path() == '/' ? 'active' : '' }}"><a href="{{ url('/') }}">Главная</a></li>
            @guest
                <li class="{{ Request::path() == 'login' ? 'active' : '' }}"><a href="{{ route('login') }}">Авторизация</a></li>
                <li class="{{ Request::path() == 'register' ? 'active' : '' }}"><a href="{{ route('register') }}">Регистрация</a></li>
            @endguest
        </ul>
      
        @if (Auth::check())
        <ul class="nav pull-right">
            <li><a>{{ Auth::user()->name }}</a></li>
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    Выход
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
        @endif
    </div>
</div>

<div class="row-fluid">
    @yield('content')
</div>

<script type="text/x-template" id="modal-template">
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              default header
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              default body
            </slot>
          </div>

          <div class="modal-footer">
            <slot name="footer">
              <button class="btn btn-primary" @click="$emit('close')">
                Закрыть
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</script>

    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
