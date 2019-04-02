<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class FavouritesTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    /**
     * @test
     */
    public function guest_can_not_favourite_any_reply()
    {
        $this->post('replies/1/favourites')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_favourite_any_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->post('replies/' . $reply->id . '/favourites');
        $this->assertCount(1, $reply->favourites);
    }
 /**
     * @test
     */
    public function an_authenticated_user_may_favourite_a_reply_once()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply');
        try{
        $this->post('replies/' . $reply->id . '/favourites');
        $this->post('replies/' . $reply->id . '/favourites');
        }catch (\Exception $e){
            $this->fail('Did not expect to insert the same record twice');
        }
        $this->assertCount(1, $reply->favourites);

    }

}
