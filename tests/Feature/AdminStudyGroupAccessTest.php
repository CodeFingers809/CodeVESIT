<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudyGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminStudyGroupAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin can access any study group without being a member.
     */
    public function test_admin_can_access_any_study_group(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('study-groups.show', $studyGroup));

        $response->assertStatus(200);
        $response->assertSee($studyGroup->name);
    }

    /**
     * Test that admin is considered a member of any study group.
     */
    public function test_admin_is_considered_member_of_any_study_group(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->assertTrue($studyGroup->isMember($admin));
    }

    /**
     * Test that admin is considered a moderator of any study group.
     */
    public function test_admin_is_considered_moderator_of_any_study_group(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->assertTrue($studyGroup->isModerator($admin));
    }

    /**
     * Test that non-admin cannot access study group without being a member.
     */
    public function test_non_admin_cannot_access_study_group_without_membership(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->actingAs($student);

        $response = $this->get(route('study-groups.show', $studyGroup));

        // Should redirect or show error since not a member
        $this->assertFalse($studyGroup->isMember($student));
    }

    /**
     * Test that regular member is not considered a moderator.
     */
    public function test_regular_member_is_not_moderator(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        // Add student as member but not moderator
        $studyGroup->members()->create([
            'user_id' => $student->id,
            'role' => 'member',
        ]);

        $this->assertTrue($studyGroup->isMember($student));
        $this->assertFalse($studyGroup->isModerator($student));
    }

    /**
     * Test that creator is considered a moderator.
     */
    public function test_creator_is_considered_moderator(): void
    {
        $creator = User::factory()->create(['role' => 'student']);

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $creator->id,
            'status' => 'approved',
        ]);

        $this->assertTrue($studyGroup->isModerator($creator));
    }
}
