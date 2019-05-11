<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    /**
     * @test
     */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->post(route('locked-threads.store',$thread))->assertStatus(403);
        $this->assertFalse(!!$thread->fresh()->locked);
    }

    /**
     * @test
     */
    public function administrators_may_lock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->post(route('locked-threads.store',$thread));
        $this->assertTrue(!!$thread->fresh()->locked,'failed asserting that the thread was locked');
    }

    /**
     * @test
     */
    public function administrators_may_unlock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id(),'locked'=>true]);
        $this->delete(route('locked-threads.destroy',$thread));
        $this->assertFalse(!! $thread->fresh()->locked,'failed asserting that the thread was locked');
    }

    /**
     * @test
     */
    public function once_locked_a_thread_may_not_receive_a_reply()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->lock();
        $this->post($thread->path() . '/replies', [
            'body' => 'Mohammed Elattar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

}
