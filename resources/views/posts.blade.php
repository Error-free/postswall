@extends('layouts.app')

@section('content')
<div class="row-fluid">
    <div class="span2"></div>
    <div class="span8">

        <div id="app" v-on:onload="window.app.loadPostList()">

            <div v-if="user_id != 0">

                <div class="alert alert-error" v-show="postForm.messageError">
                    @{{postForm.messageError}}
                </div>

                <div class="control-group">
                    <textarea style="width: 100%; height: 50px;" id="message" placeholder="Ваше сообщение..." name="message" v-model="postForm.message"></textarea>
                </div>

                <div class="control-group">
                    <button class="btn btn-primary" v-on:click="sendPost">Отправить сообщение</button>
                    <span v-show="cypher.password">
                        <input type="checkbox" name="is_private" value="1" v-model="postForm.is_private" id="is_private"> <label for="is_private" class="help-inline">Приватное</label>
                    </span>
                    <button class="btn" v-on:click="clearPostData" v-show="postForm.id">Отменить редактирование</button>
                    <button id="show-modal" class="btn" @click="showModal = true">Ключ шифрования</button>
                </div>

                
                <modal v-if="showModal" @close="showModal = false">
                  <!--
                    you can use custom content here to overwrite
                    default content
                  -->
                  <h3 slot="header">Ключ шифрования</h3>
                  <div slot="body">
                      <input class="cypher-button" type="password" v-model="passwordInput" />
                  </div>
                  <div slot="footer">
                      <button class="btn btn-primary" @click="setPassword">
                        ОК
                      </button>
                      <button class="btn btn-primary" @click="showModal = false">
                        Закрыть
                      </button>
                  </div>

                </modal>
            </div>

            <post-item
                  v-for="(item, index) in filteredPosts"
                  v-bind:post="item"
                  v-bind:index="index"
                  v-bind:key="item.id"
                  v-bind:user_id="user_id"
                  >
            </post-item>
        </div>

    </div>
</div>
@endsection