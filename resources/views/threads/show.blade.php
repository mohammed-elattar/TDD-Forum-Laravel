@extends('layouts.app')
@section('header')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
    <script>
        window.thread = <?= json_encode($thread)?>
    </script>
@endsection
@section('content')
    <thread-view inline-template :initial-replies-count="{{$thread->replies_count}}">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">

                                <img src="{{$thread->creator->avatar_path}}" width="35" height="35" class="mr-2"/>
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
                    <replies
                            @added="repliesCount++"
                            @removed="repliesCount--">
                    </replies>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>This Thread was published {{$thread->created_at->diffForHumans()}} by
                                <a href="#">{{$thread->creator->name}}</a>
                                and has <span
                                        v-text="repliesCount"></span> {{str_plural('comment',$thread->replies_count)}}
                            </p>
                            <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
