<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread ,Spam $spam)
    {
        $this->validate(request(), [
            'body' => 'required',
        ]);

        $spam->detect(request('body'));
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left');
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
        $this->authorize('update', $reply);
        $reply->update(request(['body']));
    }
}
