<?php

namespace Tests\Feature;

use App\Activity;
use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    public function setUp()
    {
        parent::setUp();
        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn('true');
            });
        });

    }

    /**
     * @test
     */
    public function guest_may_not_create_threads()
    {
        $this->withoutExceptionHandling();
        $this->expectException("Illuminate\Auth\AuthenticationException");
        $thread = make("App\Thread");
        $this->post(route('threads'), $thread->toArray());
        $this->assertRedirect(route('login'));
        $response = $this->get('threads/create');
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function new_users_must_first_confirm_their_email_address_before_creating_a_thread()
    {
        $user = factory('App\User')->states('unconfirmed')->create();
        $this->signIn($user);
        $thread = make('App\Thread');
        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'you must first confirm your email');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_add_new_forum_thread()
    {
        $this->withoutExceptionHandling();
        $response = $this->publishThread(['title'=>'some title','body'=>'some body']);
        $this->get($response->headers->get('location'))
            ->assertSee('some title')->assertSee('some body');
    }

    /**
     * @test
     */
    public function a_thread_require_unique_slug()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Title']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);

    }

    /**
     * @test
     */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Title 24']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title-24');
        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("foo-title-24-{$thread['id']}", $thread['slug']);
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
    public function a_thread_require_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);
        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /**
     * @test
     */
    public function a_thread_require_valid_channel()
    {
        factory("App\Channel", 2)->create();
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /**
     * @param $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post(route('threads'), $thread->toArray()+ ['g-recaptcha-response' => 'token']);
    }

    /**
     * @test
     */
    public function authorised_user_can_delete_thread()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->json('DELETE', $thread->path())
            ->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

    /**
     * @test
     */
    public function unauthorised_user_may_not_delete_posts()
    {
        $thread = create("App\Thread");
        $this->delete($thread->path())
            ->assertRedirect(route('login'));
        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }

}
