<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class CreateThreadsTest extends TestCase
{
  use DatabaseMigrations;
  use InteractsWithExceptionHandling;

  /**
   * @test
   */
  public function an_authenticated_user_can_add_new_forum_thread()
  {
    $this->signIn();
    $thread = make("App\Thread");
    $response = $this->post("/threads", $thread->toArray());
    $this->get($response->headers->get('location'))
      ->assertSee($thread->title)->assertSee($thread->body);
  }

  /**
   * @test
   */
  public function guest_may_not_create_threads()
  {
    $this->withoutExceptionHandling();
    $this->expectException("Illuminate\Auth\AuthenticationException");
    $thread = make("App\Thread");
    $this->post("/threads", $thread->toArray());
    $this->assertRedirect('/login');
    $response = $this->get('threads/create');
    $response->assertRedirect('/login');
  }

  /**
   * @test
   */
  public function a_thread_require_title()
  {
    $this->publishThread(['title' => null])
      ->assertSessionHasErrors('title');
  }

  /**
   * @test
   */
  public function a_thread_require_body()
  {
    $this->publishThread(['body' => null])
      ->assertSessionHasErrors('body');
  }
  /**
   * @test
   */
  public function a_thread_require_valid_channel()
  {
    factory("App\Channel",2)->create();
    $this->publishThread(['channel_id' => null])
      ->assertSessionHasErrors('channel_id');

    $this->publishThread(['channel_id' => 999])
      ->assertSessionHasErrors('channel_id');
  }

  /**
   * @param $overrides array
   */
  public function publishThread($overrides)
  {
    $this->signIn();
    $thread = make('App\Thread', $overrides);
    return $this->post('/threads', $thread->toArray());
  }
}
