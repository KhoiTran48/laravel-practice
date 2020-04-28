<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Notifications\OTPNotification;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifyOTPTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
    */

    public function an_user_can_submit_otp_and_get_verified()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $OTP = auth()->user()->cacheTheOTP();

        $this->post('/verify_otp', ['otp' => $OTP])->assertRedirect("/home");
        $this->assertDatabaseHas('users', ['verified' => 1]);
    }

    /**
     * @test
    */

    public function user_can_see_otp_verify_page()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->get('/verify_otp')
        ->assertStatus(200)
        ->assertSee("Enter OTP");
    }

    /**
     * @test
    */

    public function invalid_otp_returns_errors_message()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $OTP = auth()->user()->cacheTheOTP();

        $this->post('/verify_otp', ['otp' => "InvalidOTP"])->assertSessionHasErrors();
    }

    /**
     * @test
    */

    public function if_no_opt_is_given_then_it_return_with_error()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $OTP = auth()->user()->cacheTheOTP();

        $this->post('/verify_otp', ['otp' => null])->assertSessionHasErrors(['otp']);
    }

    /**
     * @test
    */

    public function an_otp_notification_is_send_when_user_request_new_otp()
    {
        Notification::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->post('/resend_otp', ['otp_via' => 'via_sms'])->assertRedirect('/verify_otp');
        Notification::assertSentTo([$user], OTPNotification::class);
    }
    
}
