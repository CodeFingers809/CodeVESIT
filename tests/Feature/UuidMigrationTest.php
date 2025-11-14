<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UuidMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_tables_use_uuid_primary_keys(): void
    {
        $tables = [
            'users',
            'study_groups',
            'study_group_todos',
            'study_group_announcements',
            'study_group_messages',
            'study_group_calendar_events',
            'personal_calendar_events',
            'forums',
            'forum_posts',
            'forum_comments',
            'blogs',
            'blog_requests',
            'events',
            'event_requests',
            'reports',
            'notifications',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'id'),
                "Table {$table} should have an id column"
            );
        }
    }

    public function test_foreign_keys_exist(): void
    {
        // Study groups relationships
        $this->assertTrue(Schema::hasColumn('study_groups', 'created_by'));
        $this->assertTrue(Schema::hasColumn('study_groups', 'approved_by'));

        // Study group members relationships
        $this->assertTrue(Schema::hasColumn('study_group_members', 'study_group_id'));
        $this->assertTrue(Schema::hasColumn('study_group_members', 'user_id'));

        // Personal calendar events
        $this->assertTrue(Schema::hasColumn('personal_calendar_events', 'user_id'));

        // Forum relationships
        $this->assertTrue(Schema::hasColumn('forum_posts', 'forum_id'));
        $this->assertTrue(Schema::hasColumn('forum_posts', 'user_id'));
        $this->assertTrue(Schema::hasColumn('forum_comments', 'forum_post_id'));
        $this->assertTrue(Schema::hasColumn('forum_comments', 'parent_id'));

        // Blog relationships
        $this->assertTrue(Schema::hasColumn('blogs', 'user_id'));
        $this->assertTrue(Schema::hasColumn('blogs', 'approved_by'));

        // Event relationships
        $this->assertTrue(Schema::hasColumn('events', 'organizer_id'));

        // Report relationships (polymorphic)
        $this->assertTrue(Schema::hasColumn('reports', 'reported_by'));
        $this->assertTrue(Schema::hasColumn('reports', 'reportable_id'));
        $this->assertTrue(Schema::hasColumn('reports', 'reportable_type'));
        $this->assertTrue(Schema::hasColumn('reports', 'reviewed_by'));
    }

    public function test_required_indexes_exist(): void
    {
        // Users table indexes
        $this->assertTrue(Schema::hasColumn('users', 'email'));
        $this->assertTrue(Schema::hasColumn('users', 'role'));

        // Study groups indexes
        $this->assertTrue(Schema::hasColumn('study_groups', 'status'));
        $this->assertTrue(Schema::hasColumn('study_groups', 'join_code'));

        // Personal calendar events indexes
        $this->assertTrue(Schema::hasColumn('personal_calendar_events', 'is_completed'));

        // Blog indexes
        $this->assertTrue(Schema::hasColumn('blogs', 'slug'));
        $this->assertTrue(Schema::hasColumn('blogs', 'is_published'));
    }
}
