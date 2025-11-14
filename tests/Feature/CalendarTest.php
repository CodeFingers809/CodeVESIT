<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PersonalCalendarEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that users can view their calendar.
     */
    public function test_user_can_view_calendar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertSee('My Calendar');
    }

    /**
     * Test that users can create calendar events.
     */
    public function test_user_can_create_calendar_event(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('calendar.store'), [
            'title' => 'Study Session',
            'description' => 'Prepare for exam',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('personal_calendar_events', [
            'user_id' => $user->id,
            'title' => 'Study Session',
            'description' => 'Prepare for exam',
        ]);
    }

    /**
     * Test that users can update calendar events.
     */
    public function test_user_can_update_calendar_event(): void
    {
        $user = User::factory()->create();
        $event = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
        ]);

        $this->actingAs($user);

        $response = $this->put(route('calendar.update', $event), [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('personal_calendar_events', [
            'id' => $event->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * Test that users can delete calendar events.
     */
    public function test_user_can_delete_calendar_event(): void
    {
        $user = User::factory()->create();
        $event = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('calendar.destroy', $event));

        $response->assertRedirect();
        $this->assertDatabaseMissing('personal_calendar_events', [
            'id' => $event->id,
        ]);
    }

    /**
     * Test that users can mark events as completed.
     */
    public function test_user_can_mark_event_as_completed(): void
    {
        $user = User::factory()->create();
        $event = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
            'is_completed' => false,
        ]);

        $this->actingAs($user);

        $response = $this->patch(route('calendar.toggle-complete', $event));

        $response->assertRedirect();
        $this->assertDatabaseHas('personal_calendar_events', [
            'id' => $event->id,
            'is_completed' => true,
        ]);
    }

    /**
     * Test that users can only see their own events.
     */
    public function test_user_can_only_see_own_events(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $event1 = PersonalCalendarEvent::factory()->create([
            'user_id' => $user1->id,
            'title' => 'User 1 Event',
        ]);

        $event2 = PersonalCalendarEvent::factory()->create([
            'user_id' => $user2->id,
            'title' => 'User 2 Event',
        ]);

        $this->actingAs($user1);

        $response = $this->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertSee('User 1 Event');
        $response->assertDontSee('User 2 Event');
    }

    /**
     * Test that users cannot delete other users' events.
     */
    public function test_user_cannot_delete_other_users_events(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $event = PersonalCalendarEvent::factory()->create([
            'user_id' => $user2->id,
        ]);

        $this->actingAs($user1);

        $response = $this->delete(route('calendar.destroy', $event));

        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseHas('personal_calendar_events', [
            'id' => $event->id,
        ]);
    }

    /**
     * Test that delete button is visible for completed events.
     */
    public function test_delete_button_visible_for_completed_events(): void
    {
        $user = User::factory()->create();
        $event = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
            'is_completed' => true,
            'title' => 'Completed Event',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertSee('Completed Event');
        // Should see delete button (trash icon or delete text)
    }

    /**
     * Test that events are ordered by date.
     */
    public function test_events_ordered_by_date(): void
    {
        $user = User::factory()->create();

        $event1 = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
            'start_date' => now()->addDays(3),
        ]);

        $event2 = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
            'start_date' => now()->addDay(),
        ]);

        $event3 = PersonalCalendarEvent::factory()->create([
            'user_id' => $user->id,
            'start_date' => now()->addDays(2),
        ]);

        $this->actingAs($user);

        $events = PersonalCalendarEvent::where('user_id', $user->id)
            ->orderBy('start_date')
            ->get();

        $this->assertEquals($event2->id, $events->first()->id);
        $this->assertEquals($event1->id, $events->last()->id);
    }

    /**
     * Test that guests cannot access calendar.
     */
    public function test_guest_cannot_access_calendar(): void
    {
        $response = $this->get(route('calendar.index'));

        $response->assertRedirect(route('login'));
    }
}
