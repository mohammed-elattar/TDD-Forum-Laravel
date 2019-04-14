<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class MentionUsersTest extends TestCase
{
  use DatabaseMigrations;
  use InteractsWithExceptionHandling;

  /**
   * @test
   */
  public function mentioned_users_in_reply_are_notified()
  {
      $john = create('App\User',['name'=>'JohnDoe']);
      $this->signIn($john);
      $jane = create('App\User',['name'=>'JaneDoe']);
      $thread = create('App\Thread');
      $reply = make('App\Reply',['body'=>'@JaneDoe look at this']);
      $this->json('POST',$thread->path().'/replies',$reply->toArray());
      $this->assertCount(1,$jane->notifications);
  }

}
