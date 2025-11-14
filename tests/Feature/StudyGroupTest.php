<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudyGroup;
use App\Models\StudyGroupMember;
use App\Models\StudyGroupAnnouncement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudyGroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that students can create study group requests.
     */
    public function test_student_can_create_study_group_request(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student);

        $response = $this->post(route('study-groups.store'), [
            'name' => 'Test Study Group',
            'description' => 'A group for testing',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('study_groups', [
            'name' => 'Test Study Group',
            'created_by' => $student->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that study groups have unique join codes.
     */
    public function test_study_groups_have_unique_join_codes(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student);

        $response1 = $this->post(route('study-groups.store'), [
            'name' => 'Study Group 1',
            'description' => 'Description 1',
        ]);

        $response2 = $this->post(route('study-groups.store'), [
            'name' => 'Study Group 2',
            'description' => 'Description 2',
        ]);

        $group1 = StudyGroup::where('name', 'Study Group 1')->first();
        $group2 = StudyGroup::where('name', 'Study Group 2')->first();

        $this->assertNotEquals($group1->join_code, $group2->join_code);
    }

    /**
     * Test that admin can approve study group requests.
     */
    public function test_admin_can_approve_study_group_request(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $student->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.study-groups.approve', $studyGroup));

        $response->assertRedirect();
        $this->assertDatabaseHas('study_groups', [
            'id' => $studyGroup->id,
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);
    }

    /**
     * Test that admin can reject study group requests.
     */
    public function test_admin_can_reject_study_group_request(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $student->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.study-groups.reject', $studyGroup), [
            'reason' => 'Not appropriate',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('study_groups', [
            'id' => $studyGroup->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test that students can join study groups with join code.
     */
    public function test_student_can_join_study_group_with_code(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $studyGroup = StudyGroup::factory()->create([
            'status' => 'approved',
            'join_code' => 'ABC12345',
        ]);

        $this->actingAs($student);

        $response = $this->post(route('study-groups.join'), [
            'join_code' => 'ABC12345',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('study_group_members', [
            'study_group_id' => $studyGroup->id,
            'user_id' => $student->id,
        ]);
    }

    /**
     * Test that invalid join codes are rejected.
     */
    public function test_invalid_join_code_rejected(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student);

        $response = $this->post(route('study-groups.join'), [
            'join_code' => 'INVALID1',
        ]);

        $response->assertSessionHasErrors('join_code');
    }

    /**
     * Test that only members can view study group details.
     */
    public function test_only_members_can_view_study_group_details(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->actingAs($student);

        // Non-member should not be able to view (unless admin)
        $this->assertFalse($studyGroup->isMember($student));
    }

    /**
     * Test that moderators can create announcements.
     */
    public function test_moderators_can_create_announcements(): void
    {
        $creator = User::factory()->create(['role' => 'student']);
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->actingAs($creator);

        $response = $this->post(route('study-groups.announcements.store', $studyGroup), [
            'title' => 'Important Announcement',
            'content' => 'Please read this carefully',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('study_group_announcements', [
            'study_group_id' => $studyGroup->id,
            'user_id' => $creator->id,
            'title' => 'Important Announcement',
        ]);
    }

    /**
     * Test that non-moderators cannot create announcements.
     */
    public function test_non_moderators_cannot_create_announcements(): void
    {
        $creator = User::factory()->create(['role' => 'student']);
        $member = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $studyGroup->members()->create([
            'user_id' => $member->id,
            'role' => 'member',
        ]);

        $this->actingAs($member);

        $response = $this->post(route('study-groups.announcements.store', $studyGroup), [
            'title' => 'Unauthorized Announcement',
            'content' => 'Should not be created',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test that study group breadcrumbs are consistent.
     */
    public function test_study_group_breadcrumbs_are_consistent(): void
    {
        $creator = User::factory()->create(['role' => 'student']);
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
            'name' => 'Test Group',
        ]);

        $this->actingAs($creator);

        // Check todos page breadcrumb
        $response = $this->get(route('study-groups.todos', $studyGroup));
        $response->assertStatus(200);
        $response->assertSee('Test Group');
        $response->assertSee('Todos');

        // Check calendar page breadcrumb
        $response = $this->get(route('study-groups.calendar', $studyGroup));
        $response->assertStatus(200);
        $response->assertSee('Test Group');
        $response->assertSee('Calendar');

        // Check settings page breadcrumb
        $response = $this->get(route('study-groups.settings', $studyGroup));
        $response->assertStatus(200);
        $response->assertSee('Test Group');
        $response->assertSee('Settings');
    }

    /**
     * Test that creator is automatically a moderator.
     */
    public function test_creator_is_automatically_moderator(): void
    {
        $creator = User::factory()->create(['role' => 'student']);
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->assertTrue($studyGroup->isModerator($creator));
    }

    /**
     * Test that members can leave study groups.
     */
    public function test_members_can_leave_study_group(): void
    {
        $creator = User::factory()->create(['role' => 'student']);
        $member = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $studyGroup->members()->create([
            'user_id' => $member->id,
            'role' => 'member',
        ]);

        $this->actingAs($member);

        $response = $this->delete(route('study-groups.leave', $studyGroup));

        $response->assertRedirect();
        $this->assertDatabaseMissing('study_group_members', [
            'study_group_id' => $studyGroup->id,
            'user_id' => $member->id,
        ]);
    }

    /**
     * Test that study groups can be deactivated.
     */
    public function test_study_groups_can_be_deactivated(): void
    {
        $creator = User::factory()->create(['role' => 'student']);
        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($creator);

        $response = $this->patch(route('study-groups.deactivate', $studyGroup));

        $response->assertRedirect();
        $this->assertDatabaseHas('study_groups', [
            'id' => $studyGroup->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test that only approved study groups are shown in index.
     */
    public function test_only_approved_study_groups_shown_in_index(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $approvedGroup = StudyGroup::factory()->create([
            'status' => 'approved',
            'name' => 'Approved Group',
        ]);

        $pendingGroup = StudyGroup::factory()->create([
            'status' => 'pending',
            'name' => 'Pending Group',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('study-groups.index'));

        $response->assertStatus(200);
        $response->assertSee('Approved Group');
        $response->assertDontSee('Pending Group');
    }
}
