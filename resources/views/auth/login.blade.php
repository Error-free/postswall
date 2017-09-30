@extends('layouts.app')

@section('content')
<div class="span4"></div>
<div class="span3">
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="control-group">
            <b>Авторизация</b>
        </div>
        <div class="control-group{{ $errors->has('email') ? ' error' : '' }}">
            <input type="email" id="email" name="email" placeholder="Email" data-cip-id="email"
                   autocomplete="off" autofocus required value="{{ old('email') }}">
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
            <label class="checkbox">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Запомнить меня
            </label>
            <button type="submit" class="btn btn-primary">Вход</button>
        </div>
    </form>
</div>
@endsection
