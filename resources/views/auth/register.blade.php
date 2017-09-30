@extends('layouts.app')

@section('content')

<div class="span4"></div>
<div class="span8">
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}
        <div class="control-group">
            <b>Регистрация</b>
        </div>

        <div class="control-group{{ $errors->has('name') ? ' error' : '' }}">
            <input type="text" id="name" name="name" placeholder="Логин" value="{{ old('name') }}" 
                required autofocus
                data-cip-id="inputLogin"
                autocomplete="off">
            @if ($errors->has('name'))
                <span class="help-inline">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <div class="control-group{{ $errors->has('email') ? ' error' : '' }}">
            <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" 
                required
                data-cip-id="inputLogin"
                autocomplete="off">
            @if ($errors->has('email'))
                <span class="help-inline">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="control-group{{ $errors->has('password') ? ' error' : '' }}">
            <input type="password" id="password" name="password" placeholder="Пароль"
                   data-cip-id="password">
            @if ($errors->has('password'))
                <span class="help-inline">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="control-group">
            <input type="password" id="password-confirm" name="password_confirmation" placeholder="Повторите пароль"
                   data-cip-id="inputPassword2">
        </div>

        <div class="control-group">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
</div>
@endsection
