<?php

namespace App\Notifications;

use App\Models\StudyGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudyGroupRejected extends Notification
{
    use Queueable;

    protected $studyGroup;
    protected $reason;

    public function __construct(StudyGroup $studyGroup, string $reason)
    {
        $this->studyGroup = $studyGroup;
        $this->reason = $reason;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Study Group Rejected',
            'message' => "Your study group '{$this->studyGroup->name}' was rejected. Reason: {$this->reason}",
            'url' => route('study-groups.index'),
            'icon' => 'x',
            'color' => 'red',
        ];
    }
}
