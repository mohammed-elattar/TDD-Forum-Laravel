@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><a href="#">{{$thread->creator->name}}</a> Posted</div>
                    <div class="card-body">
                            <article>
                                <h4>{{$thread->title}}</h4>
                                <div class="body">{{$thread->body}}</div>
                            </article>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($thread->replies as $reply)
                    @include("threads.reply")
                @endforeach
            </div>
        </div>
@if(auth()->check())
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="{{$thread->path()."/replies"}}">
                    {{csrf_field()}}
                    <div class="form-group">
                    <textarea class="form-control" placeholder="Have something to say?" rows="5" name="body"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    @else
            <p class="text-center text-dark">Please <a href="{{route("login")}}">Sign In</a> to be able to participate in the forum</p>
@endif




    </div>
@endsection
