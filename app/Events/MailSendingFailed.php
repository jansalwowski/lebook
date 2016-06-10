<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MailSendingFailed extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $failed;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($failed)
    {
        $this->failed = $failed;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
