<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Notifications\OTPNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
    */

    public function it_has_cache_key_for_otp()
    {
        $user = factory(User::class)->create();
        $this->assertEquals($user->OTPKey(), "OTP_for_1");
    }

    /**
     * @test
    */

    public function it_can_send_a_OTP_notification_to_the_user()
    {
        $user = factory(User::class)->create();
        Notification::fake();
        $user->sendOTP("via_sms");
        Notification::assertSentTo([$user], OTPNotification::class);
    }

}
