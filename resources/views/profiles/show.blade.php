@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <avatar-form :user="{{$profileUser}}"></avatar-form>
                @forelse($activites as $date => $activity)
                    <h3>{{$date}}</h3>
                    @foreach($activity as $record)
                        {{--                        @if(view()->exists("profiles.activities{$record->type}"))--}}
                        @include("profiles.activities.{$record->type}",['activity'=>$record])
                        {{--@endif--}}
                    @endforeach
                @empty
                    <p>There is no activities for this user yet</p>
                @endforelse
                {{--                {{$activitys->links()}}--}}
            </div>
        </div>
    </div>
    </div>
@endsection
