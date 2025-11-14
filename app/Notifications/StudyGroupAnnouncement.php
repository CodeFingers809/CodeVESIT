<?php

namespace App\Notifications;

use App\Models\StudyGroupAnnouncement as AnnouncementModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudyGroupAnnouncement extends Notification
{
    use Queueable;

    protected $announcement;

    public function __construct(AnnouncementModel $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Announcement',
            'message' => "New announcement in {$this->announcement->studyGroup->name}: {$this->announcement->title}",
            'study_group_id' => $this->announcement->study_group_id,
            'url' => route('study-groups.announcements', $this->announcement->study_group_id),
            'icon' => 'megaphone',
            'color' => 'orange',
        ];
    }
}
