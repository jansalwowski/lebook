<?php

namespace App\Listeners;

use App\Events\MailSendingFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertFailedMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MailSendingFailed  $event
     * @return void
     */
    public function handle(MailSendingFailed $event)
    {
        //
    }
}
