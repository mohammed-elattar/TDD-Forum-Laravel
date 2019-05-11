<?php

namespace Tests\Feature;

use function foo\func;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->signIn();
    }

    /**
     * @test
     */
    public function a_notification_is_prepared_when_a_subscribed_thread_get_a_new_reply_that_is_not_by_the_current_user()
    {

        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0, auth()->user()->notifications);
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'someBody here'
        ]);
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'someBody here'
        ]);
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * @test
     */
    public function a_user_can_fetch_their_un_read_notifications()
    {
        create(DatabaseNotification::class);
        $user = auth()->user();
        $this->assertCount(1, $this->getJson("/profiles/{$user->name}/notifications")->json());
    }

    /**
     * @test
     */
    public function a_user_can_mark_notification_as_read()
    {
        create(DatabaseNotification::class);
        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->fresh()->unreadNotifications);
            $this->delete("/profiles/{$user->name}/notifications/{$user->unreadNotifications()->first()->id}");
            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }

}
