<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * @test
    */

    public function an_opt_email_is_send_when_user_is_logged_in()
    {
        Mail::fake();
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create(["verified" => 1]);
        $res = $this->post('/login', ['email' => $user->email, 'password' => 'password']);
        Mail::assertSent(OTPMail::class);
    }

    /**
     * @test
    */

    public function an_opt_email_is_not_send_if_credentials_are_incorrect()
    {
        Mail::fake();
        $res = $this->post('/login', ['email' => "wrongEmail@gmail.com", 'password' => 'wrongPassword']);
        Mail::assertNotSent(OTPMail::class);
    }

    /**
     * @test
    */

    public function opt_is_stored_in_cache_for_the_user()
    {
        Mail::fake();
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create(["verified" => 1]);
        $res = $this->post('/login', ['email' => $user->email, 'password' => 'password']);
        $this->assertNotNull($user->OTPKey());
    }
    
}
