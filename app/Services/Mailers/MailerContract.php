<?php
namespace App\Services\Mailers;

use App\Models\User;
use Illuminate\Support\Facades\Request;

interface MailerContract {

    public function sendEmailConfirmationTo(User $user);

    public function sendEmailWelcome(User $user);

    public function deliver();
}