<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DocumentService;
use Illuminate\Http\UploadedFile;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Mockery;

class DocumentServiceTest extends TestCase
{
    protected DocumentService $documentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->documentService = new DocumentService();
    }

    /**
     * Test that file size limit is enforced.
     */
    public function test_file_size_limit_is_enforced(): void
    {
        // Create a file larger than 5MB
        $file = UploadedFile::fake()->create('document.docx', 6000); // 6MB

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File size exceeds the maximum limit of 5MB.');

        $this->documentService->uploadDocument($file);
    }

    /**
     * Test that valid files are accepted.
     */
    public function test_valid_file_is_accepted(): void
    {
        // Mock Cloudinary facade with chained methods
        Cloudinary::shouldReceive('upload')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->andReturn('https://cloudinary.com/test/document.docx');

        $file = UploadedFile::fake()->create('document.docx', 2000); // 2MB

        $result = $this->documentService->uploadDocument($file);

        $this->assertIsString($result);
        $this->assertStringContainsString('cloudinary.com', $result);
    }

    /**
     * Test that custom folder can be specified.
     */
    public function test_custom_folder_can_be_specified(): void
    {
        // Mock Cloudinary facade with chained methods
        Cloudinary::shouldReceive('upload')
            ->once()
            ->with(Mockery::any(), Mockery::on(function ($options) {
                return $options['folder'] === 'custom_folder';
            }))
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->andReturn('https://cloudinary.com/custom_folder/document.docx');

        $file = UploadedFile::fake()->create('document.docx', 1000);

        $result = $this->documentService->uploadDocument($file, 'custom_folder');

        $this->assertStringContainsString('cloudinary.com', $result);
    }

    /**
     * Test that default folder is 'blogs'.
     */
    public function test_default_folder_is_blogs(): void
    {
        // Mock Cloudinary facade with chained methods
        Cloudinary::shouldReceive('upload')
            ->once()
            ->with(Mockery::any(), Mockery::on(function ($options) {
                return $options['folder'] === 'blogs';
            }))
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->andReturn('https://cloudinary.com/blogs/document.docx');

        $file = UploadedFile::fake()->create('document.docx', 1000);

        $this->documentService->uploadDocument($file);

        $this->assertTrue(true); // If we get here, the test passed
    }

    /**
     * Test max file size constant.
     */
    public function test_max_file_size_constant(): void
    {
        $this->assertEquals(5 * 1024 * 1024, DocumentService::MAX_FILE_SIZE);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
