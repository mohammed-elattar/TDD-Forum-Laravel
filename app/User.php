<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email'
    ];

    protected $casts = [
      'confirmed'=>'boolean'
    ];
    public function getRouteKeyName()
    {
     return 'name';
    }

    public function threads(){
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity(){
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread){
        return sprintf("user.%s.visits.%s",$this->id,$thread->id);
    }

    public function read($thread){
        $key = $this->visitedThreadCacheKey($thread);
        cache()->forever($key,\Carbon\Carbon::now());
    }

    public function lastReply(){
        return $this->hasOne(Reply::class)->latest()->first();
    }

    public function getAvatarPathAttribute($avatar){
        $imgPath = $avatar ?:'avatars/default.png';
        return asset("storage/".$imgPath);
    }

    public function confirm(){
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function isAdmin(){
        return  in_array($this->name,['attar']);
    }
}
