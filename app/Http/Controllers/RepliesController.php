<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\User;
use App\Http\Requests\CreatePostRequest;
use App\Inspections\Spam;
use Carbon\Carbon;
use Illuminate\Auth\Access\Gate;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth", ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($channelId, Thread $thread, CreatePostRequest $request)
    {
        if($thread->locked){
            return response('Thread is locked',422);
        }
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        if (request()->expectsJson()) {
            return response(['status' => 'Reply Deleted']);
        }
        return back();
    }

    public function update(Reply $reply)
    {
        request()->validate([
            'body' => 'required|spamfree',
        ]);
        $this->authorize('update', $reply);
        $reply->update(request(['body']));
    }
}
