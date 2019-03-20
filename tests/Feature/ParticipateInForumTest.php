<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
  use InteractsWithExceptionHandling;
  use DatabaseMigrations;
    /**
     * @test
     */
    public function unauthenticated_user_may_not_add_replies(){
      $this->withoutExceptionHandling();
      $this->expectException("Illuminate\Auth\AuthenticationException");
      $this->post("threads/somechannel/1/replies",[]);
    }
    /**
     * @test
     */
    public function an_authenticated_user_may_participate_in_forum_threads(){
      $this->signIn();
      $thread = factory("App\Thread")->create();

      $reply = factory("App\Reply")->make();

      $this->post($thread->path()."/replies",$reply->toArray());

      $this->get($thread->path())->assertSee($reply->body);
    }

    /**
     * @test
     */
    public function a_reply_require_a_body(){
      $this->withExceptionHandling();
      $this->signIn();
      $thread = create("App\Thread");
      $reply = make("App\Reply",['body'=>null]);
      $this->post($thread->path()."/replies",$reply->toArray())
      ->assertSessionHasErrors('body');
    }
}
