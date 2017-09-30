@extends('layouts.app')

@section('content')
<div class="row-fluid">
    <div class="span2"></div>
    <div class="span8">

        <div id="app" v-on:onload="window.app.loadPostList()">

            <div class="alert alert-error" v-show="postForm.messageError">
                @{{postForm.messageError}}
            </div>

            <div class="control-group">
                <textarea style="width: 100%; height: 50px;" id="message" placeholder="Ваше сообщение..." name="message" v-model="postForm.message"></textarea>
            </div>

            <div class="control-group">
                <button class="btn btn-primary" v-on:click="sendPost">Отправить сообщение</button>
                <input type="checkbox" name="is_private" value="1" v-model="postForm.is_private"> Приватное
                <button class="btn" v-on:click="clearPostData" v-show="postForm.id">Отменить редактирование</button>
            </div>

            <post-item
                  v-for="(item, index) in postList"
                  v-bind:post="item"
                  v-bind:index="index"
                  v-bind:key="item.id"
                  >
            </post-item>
        </div>

    </div>
</div>
@endsection