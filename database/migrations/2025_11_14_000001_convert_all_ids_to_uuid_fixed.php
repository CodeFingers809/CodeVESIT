<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // IMPORTANT: This migration converts all IDs to UUIDs
        // This is a destructive operation and will clear all data
        // Only run this on a fresh database or after backing up

        // Disable foreign key checks (works for both MySQL and SQLite)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } else {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // ==== Drop and recreate auth tables with UUID support ====
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        // Recreate password reset tokens with UUID
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Recreate sessions with UUID
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ==== Users table ====
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
        });

        // Add indexes for performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('role');
            $table->index(['department', 'year']);
        });

        // ==== Study Groups ====
        Schema::table('study_groups', function (Blueprint $table) {
            $table->dropForeign(['created_by', 'approved_by']);
            $table->dropColumn(['id', 'created_by', 'approved_by']);
        });
        Schema::table('study_groups', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('created_by')->after('description');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add indexes
        Schema::table('study_groups', function (Blueprint $table) {
            $table->index('status');
            $table->index('join_code');
            $table->index('is_active');
        });

        // ==== Study Group Members ====
        Schema::table('study_group_members', function (Blueprint $table) {
            $table->dropForeign(['study_group_id', 'user_id']);
            $table->dropColumn(['study_group_id', 'user_id']);
        });
        Schema::table('study_group_members', function (Blueprint $table) {
            $table->uuid('study_group_id')->first();
            $table->uuid('user_id')->after('study_group_id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add composite index for lookups
        Schema::table('study_group_members', function (Blueprint $table) {
            $table->index(['study_group_id', 'user_id']);
        });

        // ==== Study Group Moderators ====
        Schema::table('study_group_moderators', function (Blueprint $table) {
            $table->dropForeign(['study_group_id', 'user_id']);
            $table->dropColumn(['study_group_id', 'user_id']);
        });
        Schema::table('study_group_moderators', function (Blueprint $table) {
            $table->uuid('study_group_id')->first();
            $table->uuid('user_id')->after('study_group_id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add composite index
        Schema::table('study_group_moderators', function (Blueprint $table) {
            $table->index(['study_group_id', 'user_id']);
        });

        // ==== Study Group Todos ====
        Schema::table('study_group_todos', function (Blueprint $table) {
            $table->dropForeign(['study_group_id']);
            $table->dropColumn(['id', 'study_group_id']);
        });
        Schema::table('study_group_todos', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('study_group_id')->after('id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
        });

        // ==== User Todo Completion ====
        Schema::table('user_todo_completions', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'study_group_todo_id']);
            $table->dropColumn(['user_id', 'study_group_todo_id']);
        });
        Schema::table('user_todo_completions', function (Blueprint $table) {
            $table->uuid('user_id')->first();
            $table->uuid('study_group_todo_id')->after('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('study_group_todo_id')->references('id')->on('study_group_todos')->onDelete('cascade');
        });

        // ==== Study Group Announcements ====
        Schema::table('study_group_announcements', function (Blueprint $table) {
            $table->dropForeign(['study_group_id', 'user_id']);
            $table->dropColumn(['id', 'study_group_id', 'user_id']);
        });
        Schema::table('study_group_announcements', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('study_group_id')->after('id');
            $table->uuid('user_id')->after('study_group_id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // ==== Study Group Messages ====
        Schema::table('study_group_messages', function (Blueprint $table) {
            $table->dropForeign(['study_group_id', 'user_id']);
            $table->dropColumn(['id', 'study_group_id', 'user_id']);
        });
        Schema::table('study_group_messages', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('study_group_id')->after('id');
            $table->uuid('user_id')->after('study_group_id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // ==== Study Group Calendar Events ====
        Schema::table('study_group_calendar_events', function (Blueprint $table) {
            $table->dropForeign(['study_group_id']);
            $table->dropColumn(['id', 'study_group_id']);
        });
        Schema::table('study_group_calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('study_group_id')->after('id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
        });

        // ==== Personal Calendar Events ====
        Schema::table('personal_calendar_events', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['id', 'user_id']);
        });
        Schema::table('personal_calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('user_id')->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add index for date queries
        Schema::table('personal_calendar_events', function (Blueprint $table) {
            $table->index(['user_id', 'start_date']);
            $table->index('is_completed');
        });

        // ==== Forums ====
        Schema::table('forums', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('forums', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
        });

        // Add index
        Schema::table('forums', function (Blueprint $table) {
            $table->index('is_active');
        });

        // ==== Forum Posts ====
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropForeign(['forum_id', 'user_id']);
            $table->dropColumn(['id', 'forum_id', 'user_id']);
        });
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('forum_id')->after('id');
            $table->uuid('user_id')->after('forum_id');
            $table->foreign('forum_id')->references('id')->on('forums')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add indexes for listing posts
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->index(['forum_id', 'created_at']);
        });

        // ==== Forum Comments ====
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->dropForeign(['forum_post_id', 'user_id', 'parent_id']);
            $table->dropColumn(['id', 'forum_post_id', 'user_id', 'parent_id']);
        });
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('forum_post_id')->after('id');
            $table->uuid('user_id')->after('forum_post_id');
            $table->uuid('parent_id')->nullable()->after('content');
            $table->foreign('forum_post_id')->references('id')->on('forum_posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('forum_comments')->onDelete('cascade');
        });

        // Add indexes
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->index(['forum_post_id', 'parent_id']);
        });

        // ==== Blogs ====
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'approved_by']);
            $table->dropColumn(['id', 'user_id', 'approved_by']);
        });
        Schema::table('blogs', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('user_id')->after('id');
            $table->uuid('approved_by')->nullable()->after('is_published');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add indexes
        Schema::table('blogs', function (Blueprint $table) {
            $table->index(['is_published', 'published_at']);
            $table->index('slug');
        });

        // ==== Blog Requests ====
        Schema::table('blog_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'approved_by']);
            $table->dropColumn(['id', 'user_id', 'approved_by']);
        });
        Schema::table('blog_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('user_id')->after('id');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add index
        Schema::table('blog_requests', function (Blueprint $table) {
            $table->index('status');
        });

        // ==== Events ====
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['organizer_id']);
            $table->dropColumn(['id', 'organizer_id']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('organizer_id')->after('slug');
            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add indexes
        Schema::table('events', function (Blueprint $table) {
            $table->index(['is_published', 'start_date']);
            $table->index('slug');
        });

        // ==== Event Requests ====
        Schema::table('event_requests', function (Blueprint $table) {
            $table->dropForeign(['organizer_id', 'approved_by']);
            $table->dropColumn(['id', 'organizer_id', 'approved_by']);
        });
        Schema::table('event_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('organizer_id')->after('id');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add index
        Schema::table('event_requests', function (Blueprint $table) {
            $table->index('status');
        });

        // ==== Reports (CRITICAL FIX: Handle polymorphic relations) ====
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['reported_by', 'reviewed_by']);
            $table->dropColumn(['id', 'reported_by', 'reportable_id', 'reportable_type', 'reviewed_by']);
        });
        Schema::table('reports', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('reported_by')->after('id');
            $table->uuidMorphs('reportable');
            $table->uuid('reviewed_by')->nullable()->after('admin_notes');
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add indexes
        Schema::table('reports', function (Blueprint $table) {
            $table->index('status');
            $table->index(['reportable_type', 'reportable_id']);
        });

        // Re-enable foreign key checks (works for both MySQL and SQLite)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible
        throw new \Exception('This migration cannot be reversed. Please restore from backup.');
    }
};
