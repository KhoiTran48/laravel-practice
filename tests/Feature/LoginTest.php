<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{

    use RefreshDatabase;
    
    /**
     * @test
    */

    public function after_login_user_can_not_access_home_page_until_verified()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user);
        $this->get('/home')->assertRedirect('/verify_otp');
    }

    /**
     * @test
    */

    public function after_login_user_can_access_home_page_if_verified()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->make(["verified" => 1]);
        $this->actingAs($user);
        $this->get('/home')->assertStatus(200);
    }


}
