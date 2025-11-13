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

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Users table
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
        });

        // Study Groups
        Schema::table('study_groups', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropColumn(['id', 'creator_id']);
        });
        Schema::table('study_groups', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('creator_id')->after('description');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Study Group Members
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

        // Study Group Moderators
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

        // Study Group Todos
        Schema::table('study_group_todos', function (Blueprint $table) {
            $table->dropForeign(['study_group_id']);
            $table->dropColumn(['id', 'study_group_id']);
        });
        Schema::table('study_group_todos', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('study_group_id')->after('id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
        });

        // User Todo Completion
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

        // Study Group Announcements
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

        // Study Group Messages
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

        // Study Group Calendar Events
        Schema::table('study_group_calendar_events', function (Blueprint $table) {
            $table->dropForeign(['study_group_id']);
            $table->dropColumn(['id', 'study_group_id']);
        });
        Schema::table('study_group_calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('study_group_id')->after('id');
            $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
        });

        // Personal Calendar Events
        Schema::table('personal_calendar_events', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['id', 'user_id']);
        });
        Schema::table('personal_calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('user_id')->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Forums
        Schema::table('forums', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('forums', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
        });

        // Forum Posts
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

        // Forum Comments
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

        // Blogs
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

        // Blog Requests
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

        // Events
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['organizer_id']);
            $table->dropColumn(['id', 'organizer_id']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('organizer_id')->after('slug');
            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Event Requests
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

        // Reports
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['reported_by']);
            $table->dropColumn(['id', 'reported_by']);
        });
        Schema::table('reports', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('reported_by')->after('id');
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible
        throw new Exception('This migration cannot be reversed. Please restore from backup.');
    }
};
