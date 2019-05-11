<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class SearchTest extends TestCase
{
  use DatabaseMigrations;
  use InteractsWithExceptionHandling;

  /**
   * @test
   */
  public function a_user_can_search_threads()
  {
      config(['scout.driver'=>'algolia']);
  $search = 'foo';
  create('App\Thread',[],2);
  create('App\Thread',["body"=>"A thread with the {$search} body"],2);
  do{
      sleep(1);
      $results = $this->getJson("/threads/search?q={$search}")->json();
  }while(empty($results));
  $this->assertCount(2,$results['data']);
  Thread::latest()->take(4)->unsearchable();
  }

}
