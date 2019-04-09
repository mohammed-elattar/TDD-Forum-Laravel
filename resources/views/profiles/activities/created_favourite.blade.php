@component('profiles.activities.activity')
    @slot('heading')
      <a href="{{$activity->subject->favourited->path()}}" > {{$profileUser->name}} favourited a reply</a>
    @endslot
    @slot('body')
        <div class="body">{{$activity->subject->favourited->body}}</div>
    @endslot
@endcomponent
