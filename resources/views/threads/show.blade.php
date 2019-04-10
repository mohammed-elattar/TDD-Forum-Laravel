@extends('layouts.app')

@section('content')
    <thread-view inline-template :initial-replies-count="{{$thread->replies_count}}">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
                        <span class="flex">
                        <a href="{{route('profile',$thread->creator->name)}}">{{$thread->creator->name}}</a>
                        Posted
                            </span>
                                <span>

                            </span>
                                @can('update',$thread)
                                    <form action="{{$thread->path()}}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-primary">Delete Thread</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <article>
                                <h4>{{$thread->title}}</h4>
                                <div class="body">{{$thread->body}}</div>
                            </article>
                        </div>
                    </div>

                    {{--{{$replies->links()}}--}}
                    <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>
                    @if(auth()->check())

                        <form method="post" action="{{$thread->path()."/replies"}}" class="mt-4">
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
                            and has <span v-text="repliesCount"></span> {{str_plural('comment',$thread->replies_count)}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
