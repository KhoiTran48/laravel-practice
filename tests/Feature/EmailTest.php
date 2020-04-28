<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\OTPNotification;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
    */

    public function an_opt_notification_is_send_when_user_is_logged_in()
    {
        Notification::fake();
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $res = $this->post('/login', ['email' => $user->email, 'password' => 'password', 'otp_via' => 'via_email']);
        Notification::assertSentTo([$user], OTPNotification::class);
    }

    /**
     * @test
    */

    public function an_opt_notification_is_not_send_if_credentials_are_incorrect()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $res = $this->post('/login', ['email' => "wrongEmail@gmail.com", 'password' => 'wrongPassword']);
        Notification::assertNotSentTo([$user], OTPNotification::class);
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
