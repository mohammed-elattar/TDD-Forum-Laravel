<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    /**
     * @test
     */
    public function only_members_can_Add_Avatar()
    {
        $this->json('POST', '/api/users/1/avatar')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();
        $this->json('POST', "/api/users/" . auth()->user() . "/avatar", [
            'avatar' => 'not a valid response'
        ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function a_user_may_add_avatar_to_their_profile()
    {
        $this->signIn();
        Storage::fake('public');
        $this->json('POST', "/api/users/" . auth()->user() . "/avatar", [
            'avatar' =>$file = UploadedFile::fake()->image('avatar.jpg')
        ]);
        Storage::disk('public')->assertExists('avatars/'.$file->hashName(),auth()->user()->avatarPath);

    }

}
