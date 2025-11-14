<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudyGroup;
use App\Models\Blog;
use App\Models\Event;
use App\Models\StudyGroupAnnouncement;
use App\Notifications\StudyGroupApproved;
use App\Notifications\StudyGroupRejected;
use App\Notifications\BlogApproved;
use App\Notifications\BlogRejected;
use App\Notifications\EventApproved;
use App\Notifications\EventRejected;
use App\Notifications\StudyGroupAnnouncement as StudyGroupAnnouncementNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that study group approval notification is sent.
     */
    public function test_study_group_approval_notification_sent(): void
    {
        Notification::fake();

        $creator = User::factory()->create([]);
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'pending',
        ]);

        $creator->notify(new StudyGroupApproved($studyGroup));

        Notification::assertSentTo($creator, StudyGroupApproved::class);
    }

    /**
     * Test that study group rejection notification contains reason.
     */
    public function test_study_group_rejection_notification_contains_reason(): void
    {
        $creator = User::factory()->create([]);
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'name' => 'Test Study Group',
        ]);

        $notification = new StudyGroupRejected($studyGroup, 'Inappropriate content');
        $notificationData = $notification->toArray($creator);

        $this->assertArrayHasKey('message', $notificationData);
        $this->assertStringContainsString('Test Study Group', $notificationData['message']);
        $this->assertStringContainsString('Inappropriate content', $notificationData['message']);
        $this->assertEquals('red', $notificationData['color']);
    }

    /**
     * Test that blog approval notification is sent.
     */
    public function test_blog_approval_notification_sent(): void
    {
        Notification::fake();

        $author = User::factory()->create([]);
        $blog = Blog::factory()->create([
            'user_id' => $author->id,
        ]);

        $author->notify(new BlogApproved($blog));

        Notification::assertSentTo($author, BlogApproved::class);
    }

    /**
     * Test that blog rejection notification contains reason.
     */
    public function test_blog_rejection_notification_contains_reason(): void
    {
        $author = User::factory()->create([]);
        $blog = Blog::factory()->create([
            'user_id' => $author->id,
            'title' => 'Test Blog',
        ]);

        $notification = new BlogRejected($blog, 'Does not meet quality standards');
        $notificationData = $notification->toArray($author);

        $this->assertArrayHasKey('message', $notificationData);
        $this->assertStringContainsString('Test Blog', $notificationData['message']);
        $this->assertStringContainsString('Does not meet quality standards', $notificationData['message']);
        $this->assertEquals('red', $notificationData['color']);
    }

    /**
     * Test that event approval notification is sent.
     */
    public function test_event_approval_notification_sent(): void
    {
        Notification::fake();

        $organizer = User::factory()->create([]);
        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
        ]);

        $organizer->notify(new EventApproved($event));

        Notification::assertSentTo($organizer, EventApproved::class);
    }

    /**
     * Test that event rejection notification contains reason.
     */
    public function test_event_rejection_notification_contains_reason(): void
    {
        $organizer = User::factory()->create([]);
        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
            'title' => 'Test Event',
        ]);

        $notification = new EventRejected($event, 'Event conflicts with calendar');
        $notificationData = $notification->toArray($organizer);

        $this->assertArrayHasKey('message', $notificationData);
        $this->assertStringContainsString('Test Event', $notificationData['message']);
        $this->assertStringContainsString('Event conflicts with calendar', $notificationData['message']);
        $this->assertEquals('red', $notificationData['color']);
    }

    /**
     * Test that study group announcement notification is sent to members.
     */
    public function test_study_group_announcement_notification_sent(): void
    {
        Notification::fake();

        $creator = User::factory()->create([]);
        $member = User::factory()->create([]);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'name' => 'Test Group',
        ]);

        $announcement = StudyGroupAnnouncement::factory()->create([
            'study_group_id' => $studyGroup->id,
            'user_id' => $creator->id,
            'title' => 'Important Announcement',
            'content' => 'Meeting tomorrow',
        ]);

        $member->notify(new StudyGroupAnnouncementNotification($announcement, $studyGroup));

        Notification::assertSentTo($member, StudyGroupAnnouncementNotification::class);
    }

    /**
     * Test that notification data contains all required fields.
     */
    public function test_notification_data_structure(): void
    {
        $user = User::factory()->create();
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $user->id,
            'name' => 'Test Group',
        ]);

        $notification = new StudyGroupApproved($studyGroup);
        $data = $notification->toArray($user);

        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('url', $data);
        $this->assertArrayHasKey('icon', $data);
        $this->assertArrayHasKey('color', $data);
    }

    /**
     * Test that notifications are stored in database.
     */
    public function test_notifications_stored_in_database(): void
    {
        $user = User::factory()->create();
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $user->id,
        ]);

        $user->notify(new StudyGroupApproved($studyGroup));

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'type' => StudyGroupApproved::class,
        ]);
    }

    /**
     * Test that users can retrieve their notifications.
     */
    public function test_users_can_retrieve_notifications(): void
    {
        $user = User::factory()->create();
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $user->id,
        ]);

        $user->notify(new StudyGroupApproved($studyGroup));

        $notifications = $user->notifications;

        $this->assertCount(1, $notifications);
        $this->assertEquals(StudyGroupApproved::class, $notifications->first()->type);
    }

    /**
     * Test that unread notifications can be marked as read.
     */
    public function test_notifications_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $user->id,
        ]);

        $user->notify(new StudyGroupApproved($studyGroup));

        $notification = $user->unreadNotifications->first();
        $this->assertNull($notification->read_at);

        $notification->markAsRead();

        $this->assertNotNull($notification->fresh()->read_at);
    }
}
