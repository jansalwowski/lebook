<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    function a_user_may_register_but_must_confirm_their_email()
    {
        $this->visit('register')
            ->type('JonDoe', 'name')
            ->type('JonDoe', 'username')
            ->type('john@example.com', 'email')
            ->type('password', 'password')
            ->type('password', 'password_confirmation')
            ->press('Register');

        $this->see('Please confirm your email address.')
            ->seeInDatabase('users', ['email' => 'john@example.com', 'verified' => 0]);

        $user = User::byUsername('JonDoe');
        $this->visit("register/confirm/{$user->email_token}")
            ->see('Your email has been confirmed!')
            ->seeInDatabase('users', ['email' => 'john@example.com', 'verified' => 1]);
    }

    /**
     * @test
     */
    function user_may_login()
    {
        User::create([
            'name' => 'JohnDoe',
            'username' => 'JohnDoe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->visit('/')
            ->type('john@example.com', 'login')
            ->type('password', 'password')
            ->press('Login');

        $this->see('JohnDoe');
    }

}
