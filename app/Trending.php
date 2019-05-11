<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
class Trending extends Model
{
    public function get(){
        return array_map('json_decode',Redis::zrevrange($this->cacheKey(),0,4));
    }

    public function cacheKey(){
        return app()->environment('testing')?'testing_trending_threads':'trending_threads';
    }

    public function pushTrending($thread)
    {
        Redis::zincrby($this->cacheKey(),1,json_encode([
            'title'=>$thread->title,
            'path'=>$thread->path()
        ]));
    }

    public function reset(){
        Redis::del($this->cacheKey());
    }
}
