<?php

namespace App\Notifications;

use App\Models\StudyGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudyGroupApproved extends Notification
{
    use Queueable;

    protected $studyGroup;

    public function __construct(StudyGroup $studyGroup)
    {
        $this->studyGroup = $studyGroup;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Study Group Approved',
            'message' => "Your study group '{$this->studyGroup->name}' has been approved!",
            'study_group_id' => $this->studyGroup->id,
            'url' => route('study-groups.show', $this->studyGroup),
            'icon' => 'check',
            'color' => 'green',
        ];
    }
}
