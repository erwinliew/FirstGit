@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card align-items-center" style="padding-bottom: 20px;">
                <img width="150" src="/storage/{{ $profile->background }}">
                <img class="rounded-circle" width="100" height="100" style="margin-top: -50px" src="/storage/{{ $profile->image }}">
                <h3>{{ $user['name'] }}</h3>
                <span style="text-align: center">{{ $profile['description']}}</span>
                <div class="pt-3"><a href="/profile/edit">Edit profile</a></div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <form enctype="multipart/form-data" method="post" style="display: flex; align-items: center">
                    <img src="/storage/{{ $profile['image'] }}" class="rounded-circle" width="40" height="40" style="margin: 0 10px" />
                    <div class="form-control" style="margin: 10px 10px;background-color: #DDDDDD; cursor: pointer">
                        <a href="/post/create" style="color: inherit; text-decoration: none">Start a post</a>
                    </div>
                </form>
            </div>
            @foreach($posts as $post)
                <div class="card" style="margin: 30px 0;padding: 20px">
                    <div style="border-bottom: 1px solid grey;padding-bottom: 10px;">
                        <h3>{{ $post->title }}</h3>
                        <span>{{ $post->caption }}</span><br />
                        <a href="/post/{{$post->id}}/edit" style="margin-right: 20px">Edit</a>
                        <a href="/post/delete/{{$post->id}}">Delete</a>
                    </div>
                    <a href="/post/{{$post->id}}">
                        <img src="/storage/{{$post->image}}" class="w-100">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection