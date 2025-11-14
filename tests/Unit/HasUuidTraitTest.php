<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudyGroup;
use App\Models\Forum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasUuidTraitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that UUIDs are automatically generated for users.
     */
    public function test_user_gets_uuid_on_creation(): void
    {
        $user = User::factory()->create();

        $this->assertIsString($user->id);
        $this->assertEquals(36, strlen($user->id)); // UUID format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i',
            $user->id
        );
    }

    /**
     * Test that UUIDs are automatically generated for study groups.
     */
    public function test_study_group_gets_uuid_on_creation(): void
    {
        $user = User::factory()->create();

        $studyGroup = StudyGroup::factory()->create([
            'created_by' => $user->id,
        ]);

        $this->assertIsString($studyGroup->id);
        $this->assertEquals(36, strlen($studyGroup->id));
        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i',
            $studyGroup->id
        );
    }

    /**
     * Test that UUIDs are automatically generated for forums.
     */
    public function test_forum_gets_uuid_on_creation(): void
    {
        $forum = Forum::factory()->create();

        $this->assertIsString($forum->id);
        $this->assertEquals(36, strlen($forum->id));
        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i',
            $forum->id
        );
    }

    /**
     * Test that model key is not incrementing.
     */
    public function test_model_key_is_not_incrementing(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->getIncrementing());
    }

    /**
     * Test that model key type is string.
     */
    public function test_model_key_type_is_string(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('string', $user->getKeyType());
    }

    /**
     * Test that UUIDs are unique across multiple creations.
     */
    public function test_uuids_are_unique(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $this->assertNotEquals($user1->id, $user2->id);
        $this->assertNotEquals($user2->id, $user3->id);
        $this->assertNotEquals($user1->id, $user3->id);
    }
}
