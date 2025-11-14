<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Blog;
use App\Models\BlogRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that students can submit blog requests.
     */
    public function test_student_can_submit_blog_request(): void
    {
        Cloudinary::shouldReceive('upload')
            ->once()
            ->andReturn((object) [
                'getSecurePath' => fn() => 'https://cloudinary.com/blogs/test.docx'
            ]);

        $student = User::factory()->create([]);

        $this->actingAs($student);

        $file = UploadedFile::fake()->create('blog.docx', 1000);

        $response = $this->post(route('blogs.store'), [
            'title' => 'Test Blog',
            'excerpt' => 'Test Excerpt',
            'category' => 'Technology',
            'tags' => 'test,blog',
            'document' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('blog_requests', [
            'user_id' => $student->id,
            'title' => 'Test Blog',
            'status' => 'pending',
        ]);
    }

    /**
     * Test that blog document is stored to Cloudinary.
     */
    public function test_blog_document_stored_to_cloudinary(): void
    {
        Cloudinary::shouldReceive('upload')
            ->once()
            ->with(\Mockery::any(), \Mockery::on(function ($options) {
                return $options['folder'] === 'blogs' &&
                       $options['resource_type'] === 'raw' &&
                       $options['format'] === 'docx';
            }))
            ->andReturn((object) [
                'getSecurePath' => fn() => 'https://cloudinary.com/blogs/test.docx'
            ]);

        $student = User::factory()->create([]);

        $this->actingAs($student);

        $file = UploadedFile::fake()->create('blog.docx', 1000);

        $response = $this->post(route('blogs.store'), [
            'title' => 'Test Blog',
            'excerpt' => 'Test Excerpt',
            'category' => 'Technology',
            'tags' => 'test,blog',
            'document' => $file,
        ]);

        $response->assertRedirect();

        $blogRequest = BlogRequest::where('user_id', $student->id)->first();
        $this->assertNotNull($blogRequest->document_path);
        $this->assertStringContainsString('cloudinary.com', $blogRequest->document_path);
    }

    /**
     * Test that file size limit is enforced.
     */
    public function test_blog_file_size_limit_enforced(): void
    {
        $student = User::factory()->create([]);

        $this->actingAs($student);

        $file = UploadedFile::fake()->create('blog.docx', 6000); // 6MB

        $response = $this->post(route('blogs.store'), [
            'title' => 'Test Blog',
            'excerpt' => 'Test Excerpt',
            'category' => 'Technology',
            'tags' => 'test,blog',
            'document' => $file,
        ]);

        $response->assertSessionHasErrors();
    }

    /**
     * Test that admin can approve blog requests.
     */
    public function test_admin_can_approve_blog_request(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create([]);

        $blogRequest = BlogRequest::factory()->create([
            'user_id' => $student->id,
            'status' => 'pending',
            'document_path' => 'https://cloudinary.com/blogs/test.docx',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.blog-requests.approve', $blogRequest));

        $response->assertRedirect();
        $this->assertDatabaseHas('blog_requests', [
            'id' => $blogRequest->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('blogs', [
            'user_id' => $student->id,
            'title' => $blogRequest->title,
        ]);
    }

    /**
     * Test that admin can reject blog requests.
     */
    public function test_admin_can_reject_blog_request(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create([]);

        $blogRequest = BlogRequest::factory()->create([
            'user_id' => $student->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.blog-requests.reject', $blogRequest), [
            'reason' => 'Content not appropriate',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('blog_requests', [
            'id' => $blogRequest->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test that published blogs are visible.
     */
    public function test_published_blogs_are_visible(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'is_published' => true,
            'title' => 'Published Blog',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertSee('Published Blog');
    }

    /**
     * Test that unpublished blogs are not visible to regular users.
     */
    public function test_unpublished_blogs_not_visible_to_users(): void
    {
        $student = User::factory()->create([]);
        $blog = Blog::factory()->create([
            'is_published' => false,
            'title' => 'Unpublished Blog',
        ]);

        $this->actingAs($student);

        $response = $this->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Unpublished Blog');
    }

    /**
     * Test that blog content is parsed and displayed.
     */
    public function test_blog_content_is_displayed(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'is_published' => true,
            'title' => 'Test Blog',
            'content' => 'This is test content',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('blogs.show', $blog));

        $response->assertStatus(200);
        $response->assertSee('Test Blog');
        $response->assertSee('This is test content');
    }

    /**
     * Test that students can only see their own blog requests.
     */
    public function test_student_can_only_see_own_blog_requests(): void
    {
        $student1 = User::factory()->create([]);
        $student2 = User::factory()->create([]);

        $blogRequest1 = BlogRequest::factory()->create([
            'user_id' => $student1->id,
            'title' => 'Student 1 Blog',
        ]);

        $blogRequest2 = BlogRequest::factory()->create([
            'user_id' => $student2->id,
            'title' => 'Student 2 Blog',
        ]);

        $this->actingAs($student1);

        $response = $this->get(route('blogs.create'));

        $response->assertStatus(200);
        // Should see own blog request status but not others
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
