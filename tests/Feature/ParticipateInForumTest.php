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
    public function unauthenticated_user_may_not_add_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException("Illuminate\Auth\AuthenticationException");
        $this->post("threads/somechannel/1/replies", []);
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();
        $thread = create("App\Thread");

        $reply = make("App\Reply");

        $this->post($thread->path() . "/replies", $reply->toArray());

//        $this->get($thread->path())->assertSee($reply->body);
        $this->assertDatabaseHas('replies',['body'=>$reply->body]);
        $this->assertEquals(1,$thread->refresh()->replies_count);
    }

    /**
     * @test
     */
    public function a_reply_require_a_body()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create("App\Thread");
        $reply = make("App\Reply", ['body' => null]);
        $this->post($thread->path() . "/replies", $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * @test
     */
    public function unauthorised_user_can_not_delete_replies()
    {
        $reply = create('App\Reply');
        $this->signIn()
            ->delete('replies/' . $reply->id)
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function authorised_user_can_delete_replies()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete('replies/' . $reply->id)
            ->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0,$reply->thread->fresh()->replies_count);
    }

    /**
     * @test
     */
    public function unauthorised_user_can_not_update_replies()
    {
        $reply = create('App\Reply');
        $updatedBody = 'you been changed , fool.';
        $this->patch('replies/' . $reply->id)
            ->assertRedirect('login');
        $this->signIn()
            ->patch('replies/' . $reply->id)
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function authorised_user_can_update_replies()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $updatedBody = 'you been changed , fool.';
        $this->patch("/replies/{$reply->id}", ['body' => $updatedBody]);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedBody]);
    }
}
