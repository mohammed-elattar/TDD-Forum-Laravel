@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
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
                @foreach($replies as $reply)
                    @include("threads.reply")
                @endforeach

                <br>
                {{$replies->links()}}
                @if(auth()->check())

                    <form method="post" action="{{$thread->path()."/replies"}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Have something to say?" rows="5"
                                      name="body"></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                @else
                    <p class="text-center text-dark">Please <a href="{{route("login")}}">Sign In</a> to be able to
                        participate in the forum</p>
                @endif


            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        This Thread was published {{$thread->created_at->diffForHumans()}} by
                        <a href="#">{{$thread->creator->name}}</a>
                        and has {{$thread->replies_count}} {{str_plural('comment',$thread->replies_count)}}

                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
