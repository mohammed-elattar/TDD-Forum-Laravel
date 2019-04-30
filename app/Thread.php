<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Redis;

class Thread extends Model
{
    use RecordsActivity;
    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
        static::created(function ($thread) {
            $thread->update(['slug'=>$thread->title]);
        });

    }

    protected $appends = ['isSubscribedTo'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));
        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each->notify($reply);
    }

    public function Channel()
    {
        return $this->belongsTo(Channel::class, "channel_id");
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getisSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user = null)
    {
        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);
        if(Thread::whereSlug($slug)->exists()){
            $slug = "{$slug}-".$this->id;
        }

        $this->attributes['slug'] = $slug;
    }
    public function markBestReply(Reply $reply){
        $this->update(['best_reply_id'=>$reply->id]);
    }

    public function lock(){
        $this->update(['locked'=>true]);
    }

//    public function visits()
//    {
//        return new Visits($this);
//    }
}
