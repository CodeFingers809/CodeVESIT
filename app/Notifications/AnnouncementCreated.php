<?php

namespace App\Notifications;

use App\Models\StudyGroupAnnouncement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $announcement;
    protected $studyGroup;

    public function __construct(StudyGroupAnnouncement $announcement)
    {
        $this->announcement = $announcement;
        $this->studyGroup = $announcement->studyGroup;
    }

    public function via($notifiable)
    {
        // Only send email if user hasn't opted out
        if ($notifiable->email_notifications === false) {
            return [];
        }

        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('study-groups.announcements', $this->studyGroup);

        return (new MailMessage)
            ->subject('New Announcement in ' . $this->studyGroup->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new announcement has been posted in **' . $this->studyGroup->name . '**')
            ->line('**' . $this->announcement->title . '**')
            ->line($this->announcement->content)
            ->action('View Announcement', $url)
            ->line('Thank you for being part of the community!');
    }
}
