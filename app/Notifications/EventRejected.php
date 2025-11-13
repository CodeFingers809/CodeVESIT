<?php

namespace App\Notifications;

use App\Models\EventRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventRejected extends Notification
{
    use Queueable;

    protected $eventRequest;
    protected $reason;

    public function __construct(EventRequest $eventRequest, string $reason)
    {
        $this->eventRequest = $eventRequest;
        $this->reason = $reason;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Event Rejected',
            'message' => "Your event '{$this->eventRequest->title}' was rejected. Reason: {$this->reason}",
            'url' => route('events.index'),
            'icon' => 'x',
            'color' => 'red',
        ];
    }
}
