<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
            Schema::dropIfExists('study_group_members');
            Schema::dropIfExists('study_group_moderators');
            Schema::dropIfExists('user_todo_completions');
            Schema::dropIfExists('study_group_todos');
            Schema::dropIfExists('study_group_announcements');
            Schema::dropIfExists('study_group_messages');
            Schema::dropIfExists('study_group_calendar_events');
            Schema::dropIfExists('study_groups');
            Schema::dropIfExists('personal_calendar_events');
            Schema::dropIfExists('forum_comments');
            Schema::dropIfExists('forum_posts');
            Schema::dropIfExists('forums');
            Schema::dropIfExists('blog_requests');
            Schema::dropIfExists('blogs');
            Schema::dropIfExists('event_requests');
            Schema::dropIfExists('events');
            Schema::dropIfExists('reports');
            Schema::dropIfExists('notifications');
            Schema::dropIfExists('users');

            // Auth tables
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });

            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->uuid('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });

            // Users
            Schema::create('users', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['admin', 'moderator', 'user'])->default('user');
                $table->string('department')->nullable();
                $table->string('year')->nullable();
                $table->string('division')->nullable();
                $table->string('roll_number')->nullable();
                $table->text('bio')->nullable();
                $table->string('avatar_seed')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('email_notifications')->default(true);
                $table->rememberToken();
                $table->timestamps();

                $table->index('email');
                $table->index('role');
                $table->index(['department', 'year']);
            });

            // Study Groups
            Schema::create('study_groups', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->text('description')->nullable();
                $table->uuid('created_by');
                $table->string('subject')->nullable();
                $table->integer('max_members')->default(50);
                $table->string('join_code')->unique();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->uuid('approved_by')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->index('status');
                $table->index('join_code');
                $table->index('is_active');
            });

            // Study Group Members
            Schema::create('study_group_members', function (Blueprint $table) {
                $table->uuid('study_group_id');
                $table->uuid('user_id');
                $table->enum('role', ['member', 'moderator'])->default('member');
                $table->timestamp('joined_at')->useCurrent();
                $table->timestamp('created_at')->useCurrent();

                $table->primary(['study_group_id', 'user_id']);
                $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['study_group_id', 'user_id']);
            });

            // Study Group Moderators
            Schema::create('study_group_moderators', function (Blueprint $table) {
                $table->uuid('study_group_id');
                $table->uuid('user_id');
                $table->timestamp('appointed_at')->useCurrent();

                $table->primary(['study_group_id', 'user_id']);
                $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['study_group_id', 'user_id']);
            });

            // Study Group Todos
            Schema::create('study_group_todos', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('study_group_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('due_date')->nullable();
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
                $table->timestamps();

                $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
            });

            // User Todo Completions
            Schema::create('user_todo_completions', function (Blueprint $table) {
                $table->uuid('user_id');
                $table->uuid('study_group_todo_id');
                $table->boolean('completed')->default(false);
                $table->timestamp('completed_at')->nullable();

                $table->primary(['user_id', 'study_group_todo_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('study_group_todo_id')->references('id')->on('study_group_todos')->onDelete('cascade');
            });

            // Study Group Announcements
            Schema::create('study_group_announcements', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('study_group_id');
                $table->uuid('user_id');
                $table->string('title');
                $table->text('content');
                $table->boolean('is_pinned')->default(false);
                $table->boolean('is_locked')->default(false);
                $table->timestamps();

                $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });

            // Study Group Messages
            Schema::create('study_group_messages', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('study_group_id');
                $table->uuid('user_id');
                $table->text('message');
                $table->timestamps();

                $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });

            // Study Group Calendar Events
            Schema::create('study_group_calendar_events', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('study_group_id');
                $table->uuid('created_by');
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('event_date');
                $table->string('location')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->timestamps();

                $table->foreign('study_group_id')->references('id')->on('study_groups')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });

            // Personal Calendar Events
            Schema::create('personal_calendar_events', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('start_date');
                $table->timestamp('end_date')->nullable();
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
                $table->boolean('is_completed')->default(false);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'start_date']);
                $table->index('is_completed');
            });

            // Forums
            Schema::create('forums', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('slug')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('is_active');
            });

            // Forum Posts
            Schema::create('forum_posts', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('forum_id');
                $table->uuid('user_id');
                $table->string('title');
                $table->text('content');
                $table->boolean('is_pinned')->default(false);
                $table->boolean('is_locked')->default(false);
                $table->integer('views')->default(0);
                $table->timestamps();

                $table->foreign('forum_id')->references('id')->on('forums')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['forum_id', 'created_at']);
            });

            // Forum Comments
            Schema::create('forum_comments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('forum_post_id');
                $table->uuid('user_id');
                $table->text('content');
                $table->uuid('parent_id')->nullable();
                $table->timestamps();

                $table->foreign('forum_post_id')->references('id')->on('forum_posts')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('parent_id')->references('id')->on('forum_comments')->onDelete('cascade');
                $table->index(['forum_post_id', 'parent_id']);
            });

            // Blogs
            Schema::create('blogs', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content');
                $table->string('featured_image')->nullable();
                $table->string('document_path')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->uuid('approved_by')->nullable();
                $table->integer('views')->default(0);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['is_published', 'published_at']);
                $table->index('slug');
            });

            // Blog Requests
            Schema::create('blog_requests', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->string('title');
                $table->text('content');
                $table->string('document_path')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->uuid('approved_by')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->index('status');
            });

            // Events
            Schema::create('events', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title');
                $table->string('slug')->unique();
                $table->uuid('organizer_id');
                $table->text('description');
                $table->timestamp('start_date');
                $table->timestamp('end_date')->nullable();
                $table->string('location')->nullable();
                $table->string('featured_image')->nullable();
                $table->integer('max_participants')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamps();

                $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['is_published', 'start_date']);
                $table->index('slug');
            });

            // Event Requests
            Schema::create('event_requests', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('organizer_id');
                $table->string('title');
                $table->text('description');
                $table->timestamp('start_date');
                $table->timestamp('end_date')->nullable();
                $table->string('location')->nullable();
                $table->integer('max_participants')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->uuid('approved_by')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();

                $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->index('status');
            });

            // Reports
            Schema::create('reports', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('reported_by');
                $table->uuidMorphs('reportable');
                $table->text('reason');
                $table->enum('status', ['pending', 'reviewing', 'resolved', 'dismissed'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->uuid('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
                $table->index('status');
            });

            // Notifications
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->uuidMorphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });

        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function down(): void
    {
        throw new \Exception('This migration cannot be reversed. Please restore from backup.');
    }
};