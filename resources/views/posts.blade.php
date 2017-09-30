@extends('layouts.app')

@section('content')
<div class="row-fluid">
    <div class="span2"></div>
    <div class="span8">

        <form action="{{ url('post')}}" method="POST" class="form-horizontal" style="margin-bottom: 50px;">
            {{ csrf_field() }}

            <input type="hidden" value="0" name="id" />

            @if ($errors->has('message'))
                <div class="alert alert-error">
                    {{ $errors->first('message') }}
                </div>
            @endif

            <div class="control-group">
                <textarea style="width: 100%; height: 50px;" id="message" placeholder="Ваше сообщение..." name="message"></textarea>
            </div>

            <div class="control-group">
                <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                <input type="checkbox" name="is_private" value="1"> Приватное
            </div>
        </form>

        @foreach ($posts as $post)

        <div class="well">
            <h5>{{ $post->user->name }}:</h5>

            {{ $post->decodedMessage }}

            <br />{{ $post->created_at }}

            <form action="{{ url('post/'.$post->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger">
                    Delete
                </button>
            </form>

            <button class="btn">
                Edit
            </button>
        </div>

        @endforeach
    </div>
</div>
@endsection