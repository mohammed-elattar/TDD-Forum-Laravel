<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 4/25/19
 * Time: 10:42 AM
 */

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }
    public function record(){
        Redis::incr($this->cacheKey());
        return $this;
    }
    public function reset(){
        Redis::del($this->cacheKey());
    }
    public function count(){
        return Redis::get($this->cacheKey())??0;
    }

    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}
