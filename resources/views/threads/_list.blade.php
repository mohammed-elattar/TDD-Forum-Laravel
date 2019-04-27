@forelse($threads as $thread)
    <div class="card mb-4">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4><a href="{{$thread->path()}}">
                            @if(auth()->check()&&$thread->hasUpdatesFor(auth()->user()))
                                <strong>{{$thread->title}}</strong>
                            @else
                                {{$thread->title}}
                            @endif
                        </a></h4>
                    <h7>Posted By : <a
                                href="/profiles/{{$thread->creator->name}}">{{$thread->creator->name}}</a>
                    </h7>
                </div>
                <a href="{{$thread->path()}}">{{$thread->replies_count}} {{str_plural('reply',$thread->replies_count)}}</a>
            </div>
        </div>
        <div class="card-body">
            <article>
                <div class="body">{{$thread->body}}</div>
            </article>
        </div>
        <div class="card-footer">
{{--            {{$thread->visits()->count()}} visits--}}
            {{$thread->visits}} visits
        </div>
    </div>
@empty
    <p>Threre is no relevant results at this time</p>
@endforelse
