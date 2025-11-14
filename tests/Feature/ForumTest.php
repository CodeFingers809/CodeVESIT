<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Forum;
use App\Models\ForumPost;
use App\Models\ForumComment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForumTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that forums index page displays forums.
     */
    public function test_forums_index_displays_forums(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create([
            'name' => 'Test Forum',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('forums.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Forum');
    }

    /**
     * Test that "be the first to post" shows when no posts exist.
     */
    public function test_be_first_to_post_shows_when_no_posts(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);

        $this->actingAs($user);

        $response = $this->get(route('forums.index'));

        $response->assertStatus(200);
        $response->assertSee('Be the first to post!');
    }

    /**
     * Test that "be the first to post" does NOT show when posts exist.
     */
    public function test_be_first_to_post_hidden_when_posts_exist(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);

        ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('forums.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Be the first to post!');
    }

    /**
     * Test that forum posts can be viewed.
     */
    public function test_forum_post_can_be_viewed(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);
        $post = ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'title' => 'Test Post Title',
            'content' => 'Test Post Content',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('forums.posts.show', [$forum, $post]));

        $response->assertStatus(200);
        $response->assertSee('Test Post Title');
        $response->assertSee('Test Post Content');
    }

    /**
     * Test that authenticated users can create forum posts.
     */
    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);

        $this->actingAs($user);

        $response = $this->post(route('forums.posts.store', $forum), [
            'title' => 'New Post',
            'content' => 'New Post Content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_posts', [
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'title' => 'New Post',
            'content' => 'New Post Content',
        ]);
    }

    /**
     * Test that authenticated users can comment on posts.
     */
    public function test_authenticated_user_can_comment_on_post(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);
        $post = ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('forums.posts.comments.store', [$forum, $post]), [
            'content' => 'Test Comment',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_comments', [
            'forum_post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);
    }

    /**
     * Test that posts can be reported.
     */
    public function test_post_can_be_reported(): void
    {
        $reporter = User::factory()->create();
        $author = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);
        $post = ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $author->id,
        ]);

        $this->actingAs($reporter);

        $response = $this->post(route('forums.posts.report', [$forum, $post]), [
            'reason' => 'Inappropriate content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'reported_by' => $reporter->id,
            'reportable_type' => ForumPost::class,
            'reportable_id' => $post->id,
            'reason' => 'Inappropriate content',
        ]);
    }

    /**
     * Test that comments can be reported.
     */
    public function test_comment_can_be_reported(): void
    {
        $reporter = User::factory()->create();
        $author = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);
        $post = ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $author->id,
        ]);
        $comment = ForumComment::factory()->create([
            'forum_post_id' => $post->id,
            'user_id' => $author->id,
        ]);

        $this->actingAs($reporter);

        $response = $this->post(route('forums.posts.comments.report', [$forum, $post, $comment]), [
            'reason' => 'Spam',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'reported_by' => $reporter->id,
            'reportable_type' => ForumComment::class,
            'reportable_id' => $comment->id,
            'reason' => 'Spam',
        ]);
    }

    /**
     * Test that latestPost relationship works correctly.
     */
    public function test_latest_post_relationship_works(): void
    {
        $user = User::factory()->create();
        $forum = Forum::factory()->create(['is_active' => true]);

        $oldPost = ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'created_at' => now()->subDay(),
        ]);

        $newPost = ForumPost::factory()->create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        $forum->load('latestPost');

        $this->assertEquals($newPost->id, $forum->latestPost->id);
        $this->assertNotEquals($oldPost->id, $forum->latestPost->id);
    }

    /**
     * Test that guests cannot create posts.
     */
    public function test_guest_cannot_create_post(): void
    {
        $forum = Forum::factory()->create(['is_active' => true]);

        $response = $this->post(route('forums.posts.store', $forum), [
            'title' => 'Unauthorized Post',
            'content' => 'Should not be created',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('forum_posts', [
            'title' => 'Unauthorized Post',
        ]);
    }
}
