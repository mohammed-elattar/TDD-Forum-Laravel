@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <h1>
                    {{$profileUser->name}}
                    <small>Since {{$profileUser->created_at->diffForHumans()}}</small>
                </h1>
                @foreach($threads as $thread)
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="level">
                    <span class="flex">
                            <a href="{{route('profile',$profileUser->name)}}">
                                {{$profileUser->name}}
                            </a> posted :
                        <a href="{{$thread->path()}}">{{$thread->title}}</a>
                    </span>
                                <span class="body">{{$profileUser->created_at->diffForhumans()}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <article>
                                <div class="body">{{$thread->body}}</div>
                            </article>
                            <hr>
                        </div>
                    </div>
                @endforeach
                {{$threads->links()}}
            </div>
        </div>
    </div>
    </div>
@endsection
