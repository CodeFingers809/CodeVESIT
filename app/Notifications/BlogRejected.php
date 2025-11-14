<?php

namespace App\Notifications;

use App\Models\BlogRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlogRejected extends Notification
{
    use Queueable;

    protected $blogRequest;
    protected $reason;

    public function __construct(BlogRequest $blogRequest, string $reason)
    {
        $this->blogRequest = $blogRequest;
        $this->reason = $reason;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Blog Rejected',
            'message' => "Your blog '{$this->blogRequest->title}' was rejected. Reason: {$this->reason}",
            'url' => route('blogs.my'),
            'icon' => 'x',
            'color' => 'red',
        ];
    }
}
