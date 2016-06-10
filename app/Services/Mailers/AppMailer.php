<?php namespace App\Services\Mailers;


use App\Events\MailSendingFailed;
use Illuminate\Contracts\Mail\Mailer as MailerService;
use Illuminate\Support\Facades\Config;

class AppMailer extends Mailer implements MailerContract {

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
        $this->from = Config::get('mail.from');
    }

    public function deliver()
    {
        $from = $this->from;
        $to = $this->to;

        $data = [
            'token' => $this->data['user']->email_token
        ];
        

        $this->mailer->send($this->view, $data, function ($message) use($from, $to) {
            $message->from($from['address'], $from['name'])
                ->to($to);
        });

        if(count($this->mailer->failures()))
        {
            event(new MailSendingFailed($this->mailer->failures()));
        }
        
    }

}