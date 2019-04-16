<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Support\Facades\Redis;
class TrendingThreadsTest extends TestCase
{
  use DatabaseMigrations;
  use InteractsWithExceptionHandling;
  protected function setUp()
  {
      parent::setUp();
      Redis::del('trending_threads');
  }

    /**
   * @test
   */
  public function it_increments_thread_score_each_time_it_is_read()
  {
      $this->assertEmpty(Redis::zrevrange('trending_threads',0,-1));
      $thread = create('App\Thread');
      $this->call('Get',$thread->path());
      $trending = Redis::zrevrange('trending_threads',0,-1);
      $this->assertCount(1,$trending);
      $this->assertEquals($thread->title,json_decode($trending[0])->title);
  }

}
