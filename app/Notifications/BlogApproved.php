<?php

namespace App\Notifications;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlogApproved extends Notification
{
    use Queueable;

    protected $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Blog Approved',
            'message' => "Your blog '{$this->blog->title}' has been approved and published!",
            'blog_id' => $this->blog->id,
            'url' => route('blogs.show', $this->blog),
            'icon' => 'check',
            'color' => 'green',
        ];
    }
}
