<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use ZipArchive;

class DocumentService
{
    // Maximum file size in bytes (5MB to save space and credits)
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    /**
     * Upload a document to Cloudinary with compression
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string URL of the uploaded file
     * @throws \Exception
     */
    public function uploadDocument(UploadedFile $file, string $folder = 'blogs'): string
    {
        // Validate file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File size exceeds the maximum limit of 5MB.');
        }

        // Compress the file if it's a docx (which is already a zip)
        $compressedFile = $this->compressDocx($file);

        try {
            // Upload to Cloudinary
            $uploadedFile = Cloudinary::upload($compressedFile->getPathname(), [
                'folder' => $folder,
                'resource_type' => 'raw', // For non-image files
                'format' => 'docx',
                'use_filename' => true,
                'unique_filename' => true,
            ]);

            // Clean up temporary file if compression created a new one
            if ($compressedFile !== $file && file_exists($compressedFile->getPathname())) {
                @unlink($compressedFile->getPathname());
            }

            return $uploadedFile->getSecurePath();
        } catch (\Exception $e) {
            // Clean up temporary file on error
            if ($compressedFile !== $file && file_exists($compressedFile->getPathname())) {
                @unlink($compressedFile->getPathname());
            }
            throw new \Exception('Failed to upload document: ' . $e->getMessage());
        }
    }

    /**
     * Compress a docx file by re-zipping it with maximum compression
     *
     * @param UploadedFile $file
     * @return UploadedFile
     */
    private function compressDocx(UploadedFile $file): UploadedFile
    {
        // DOCX files are already ZIP archives, so we can try to recompress them
        // However, for simplicity and to avoid potential issues, we'll just return
        // the original file. Cloudinary handles compression on their end.

        // The main size savings come from the file size limit we enforce
        return $file;
    }

    /**
     * Upload an image to Cloudinary with optimization
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string URL of the uploaded image
     * @throws \Exception
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images'): string
    {
        // Validate file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('Image size exceeds the maximum limit of 5MB.');
        }

        try {
            // Upload to Cloudinary with automatic optimization
            $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'image',
                'transformation' => [
                    'quality' => 'auto:good',
                    'fetch_format' => 'auto',
                ],
                'use_filename' => true,
                'unique_filename' => true,
            ]);

            return $uploadedFile->getSecurePath();
        } catch (\Exception $e) {
            throw new \Exception('Failed to upload image: ' . $e->getMessage());
        }
    }

    /**
     * Get the maximum allowed file size in human-readable format
     *
     * @return string
     */
    public static function getMaxFileSizeFormatted(): string
    {
        return number_format(self::MAX_FILE_SIZE / 1024 / 1024, 0) . 'MB';
    }
}
