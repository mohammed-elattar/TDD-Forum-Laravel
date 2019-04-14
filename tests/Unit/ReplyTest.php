<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
  use DatabaseMigrations;
    /**
     * @test
     */
    public function it_has_an_owner()
    {
      $reply = create("App\Reply");
        $this->assertInstanceOf("App\User",$reply->owner);
    }
    /**
     * @test
     */
    public function it_knows_if_it_was_just_published()
    {
      $reply = create("App\Reply");
        $this->assertTrue($reply->wasJustPublished());
        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());

    }
    /**
     * @test
     */
    public function it_can_detect_all_mentioned_users_on_the_body()
    {
      $reply = create("App\Reply",['body'=>'please advise @Ruby']);

      $this->assertEquals('Ruby',$reply->mentionedUsers()[0]);

    }/**
     * @test
     */
    public function it_wraps_mentioned_user_names_in_the_body_with_in_anchor_tags()
    {
      $reply = create("App\Reply",['body'=>'Hello @Ruby']);
      $this->assertEquals('Hello <a href="/profiles/Ruby">@Ruby</a>',$reply->body);

    }
}
