<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->thread = factory("App\Thread")->create();
    }

    /**
     * @test
     */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf("Illuminate\Database\Eloquent\Collection", $this->thread->replies);
    }

    /**
     * @test
     */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf("App\User", $this->thread->creator);
    }

    /**
     * @test
     */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'foobar',
            'user_id' => 1
        ]);
        $this->assertCount(1, $this->thread->replies);
    }

    /**
     * @test
     */
    public function a_thread_belongs_to_channel()
    {
        $thread = create("App\Thread");
        $this->assertInstanceOf("App\Channel", $thread->channel);
    }

    /**
     * @test
     */
    public function a_thread_can_make_path_function()
    {
        $thread = create("App\Thread");
        $this->assertEquals("/threads/" . $thread->channel->slug . "/" . $thread->id, $thread->path());
    }

    /**
     * @test
     */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /**
     * @test
     */
    public function a_thread_can_be_un_subscribed_to()
    {
        $thread = create('App\Thread');
        $thread->unsubscribe($userId = 1);
        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /**
     * @test
     */
    public function a_thread_notifies_all_registered_subscrbers_when_reply_is_added()
    {
        \Illuminate\Support\Facades\Notification::fake();
        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foo',
                'user_id' => 999,
            ]);
        \Illuminate\Support\Facades\Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }
    /**
     * @test
     */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        tap(auth()->user() ,function ($user)use($thread){
            $this->assertTrue($thread->hasUpdatesFor($user));
            $user->read($thread);
            $this->assertFalse($thread->hasUpdatesFor($user));
        });

    }
}
