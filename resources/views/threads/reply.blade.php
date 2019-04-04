<br>
<div class="card">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
            <a href="{{route('profile',$reply->owner->name)}}">
                {{$reply->owner->name}}</a> said
            {{$reply->created_at->diffForHumans()}}
            </h5>
        <div>
            <form action="/replies/{{$reply->id}}/favourites" method="post">
                {{csrf_field()}}
                <button type="submit" class="btn btn-outline-secondary" {{$reply->isFavourited()?'disabled':''}}>
                    {{$reply->favourites_count}} {{str_plural('favourite',$reply->favourites_count)}}
                </button>
            </form>
        </div>
        </div>

    </div>
    <div class="card-body">
        <article>
            <div class="body">{{$reply->body}}</div>
        </article>
    </div>
</div>
