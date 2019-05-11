<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\MailFake;
use Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    /**
     * @test
     */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();
        $this->post(route('register'), [
            'name' => 'tito',
            'email' => 'tito@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);
        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /**
     * @test
     */
    public function user_can_fully_confirm_their_email_addresses()
    {
        $this->withoutExceptionHandling();
        $this->post('/register', [
            'name' => 'tito',
            'email' => 'tito@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $user = User::whereEmail('tito@example.com')->first();
        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('rgister.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));
        tap($user->fresh(),function($user){
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });


    }

    /**
     * @test
     */
    public function confirming_an_invalid_token(){
        $this->get(route('rgister.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
        ->assertSessionHas('flash','Unknown token');
    }

}
