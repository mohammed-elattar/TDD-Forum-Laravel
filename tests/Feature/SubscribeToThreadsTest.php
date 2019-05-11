<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    /**
     * @test
     */
    public function a_user_can_subscribe_to_threads()
    {

        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');
        $this->assertCount(1,$thread->fresh()->subscriptions);
    }
 /**
     * @test
     */
    public function it_knows_if_the_authenticated_used_subscribed_to_it()
    {

        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribedTo);
    }


    /**
     * @test
     */
    public function a_user_can_unsubscribe_from_threads()
    {

        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();
        $this->delete($thread->path().'/subscriptions');
        $this->assertCount(0, $thread->subscriptions);
    }
}
