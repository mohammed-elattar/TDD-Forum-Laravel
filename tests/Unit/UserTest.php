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
/**
   * @test
   */
  public function a_user_can_determine_his_avatar_path()
  {
  $user = create('App\User');
  $this->assertEquals(asset('storage/avatars/default.png'),$user->avatar_path);

  $user->avatar_path='avatars/me.jpg';
  $this->assertEquals(asset('storage/avatars/me.jpg'),$user->avatar_path);
  }

}
