<?php

namespace Tests\Feature;

use App\Trending;
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
      $this->trending = new Trending();
      $this->trending->reset();
  }

    /**
   * @test
   */
  public function it_increments_thread_score_each_time_it_is_read()
  {
      $this->assertEmpty($this->trending->get());
      $thread = create('App\Thread');
      $this->call('Get',$thread->path());
      $trending = $this->trending->get();
      $this->assertCount(1,$trending);
      $this->assertEquals($thread->title,$trending[0]->title);
  }

}
