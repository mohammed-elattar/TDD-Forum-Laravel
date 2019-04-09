<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="card mt-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{route('profile',$reply->owner->name)}}">
                        {{$reply->owner->name}}</a> said
                    {{$reply->created_at->diffForHumans()}}
                </h5>
                @if(auth()->check())
                    <div>
                        <favourite :reply="{{$reply}}"></favourite>
                    </div>
                @endif
            </div>

        </div>
        <div class="card-body">
            <article>
                <div v-if="editing">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body">{{$reply->body}}</textarea>
                    </div>
                    <button class="btn btn-primary" @click="update">update</button>
                    <button class="btn btn-link" @click="editing=false">Cancel</button>
                </div>
                <div class="body" v-else v-text="body"></div>
            </article>
        </div>
        @can('update',$reply)
            <div class="card-footer level">
                <button class="btn btn-outline-secondary btn-sm mr-1" @click="editing=true">Edit</button>
                <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>
