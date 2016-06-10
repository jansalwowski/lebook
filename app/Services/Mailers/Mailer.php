<?php namespace App\Services\Mailers;


use App\Models\Subscription;
use App\Models\User;

abstract class Mailer {

    protected $mailer;

    protected $from;

    protected $to;

    protected $view;

    protected $subject;

    protected $data = [];

    public function sendEmailConfirmationTo(User $user)
    {
        $this->to = $user->email;
        $this->view = 'emails.confirm';
        $this->data = compact('user');
        $this->subject = trans('emails.subjects.sendEmailConfirmationTo');

        $this->deliver();
    }

    public function sendEmailWelcome(User $user)
    {
        $this->to = $user->email;
        $this->view = 'emails.welcome';
        $this->data = compact('user');
        $this->subject = trans('emails.subjects.sendEmailWelcome');

        $this->deliver();
    }


    abstract public function deliver();
}