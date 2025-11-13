<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventApproved extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Event Approved',
            'message' => "Your event '{$this->event->title}' has been approved and published!",
            'event_id' => $this->event->id,
            'url' => route('events.show', $this->event),
            'icon' => 'check',
            'color' => 'green',
        ];
    }
}
