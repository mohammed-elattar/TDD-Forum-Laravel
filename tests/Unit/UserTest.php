<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class UserTest extends TestCase
{
  use DatabaseMigrations;
  use InteractsWithExceptionHandling;

  /**
   * @test
   */
  public function a_user_can_fetch_most_recent_replies()
  {
  $user = create('App\User');
  $reply = create('App\Reply',['user_id'=>$user->id]);
  $this->assertEquals($reply->id ,$user->lastReply()->id );
  }

}
