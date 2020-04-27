<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifyOTPTest extends TestCase
{
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

        $this->post('/verifyOTP', ['otp' => $OTP])->assertRedirect("/home");
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

        $this->get('/verifyOTP')
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

        $this->post('/verifyOTP', ['otp' => "InvalidOTP"])->assertSessionHasErrors();
    }

    /**
     * @test
    */

    public function if_no_opt_is_given_then_it_return_with_error()
    {
        // $this->withoutExceptionHandling();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $OTP = auth()->user()->cacheTheOTP();

        $this->post('/verifyOTP', ['otp' => null])->assertSessionHasErrors(['otp']);
    }
    
}
