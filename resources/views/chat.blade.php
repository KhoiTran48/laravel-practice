<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .list-group{
            overflow: scroll;
            height: 400px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container" id="app">
        <div class="offset-4 col-4 offset-sm-1 col-sm-10">
        <li class="list-group-item active">Chat Room <span class="badge badge-pill badge-warning">@{{ numberOfUser }}</span> </li>
            <div class="badge badge-pill badge-primary">@{{ typing }}</div>
            <ul class="list-group" v-chat-scroll>
                <message
                    v-for="value,index in chat.message"
                    :key=index
                    :color=chat.color[index]
                    :username=chat.username[index]
                    :time=chat.time[index]
                >
                    @{{ value }}
                </message>
            </ul>
            <input type="text" name="" id="" class="form-control" placeholder="Type your message here..."
                    v-model="message" @keyup.enter="send"
                />
            <br/>
            <a href="" class="btn btn-warning btn-sm" @click.prevent="deleteSession">Delete Chats</a>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>