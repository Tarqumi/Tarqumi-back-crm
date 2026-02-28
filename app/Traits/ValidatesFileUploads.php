<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait ValidatesFileUploads
{
    /**
     * Validate image upload
     *
     * @param UploadedFile $file
     * @param int $maxSizeMB Maximum file size in megabytes (default: 20MB)
     * @throws \Exception
     */
    protected function validateImageUpload(UploadedFile $file, int $maxSizeMB = 20): void
    {
        // Validate file size
        $maxSizeBytes = $maxSizeMB * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            throw new \Exception("Image size must not exceed {$maxSizeMB}MB");
        }

        // Validate MIME type (not just extension) - prevents disguised files
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $fileMime = $file->getMimeType();
        
        if (!in_array($fileMime, $allowedMimes)) {
            throw new \Exception('Only JPEG, PNG, GIF, WebP, and SVG images are allowed');
        }

        // Validate file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception('Invalid file extension. Only JPG, PNG, GIF, WebP, and SVG are allowed');
        }

        // Additional security: Verify file is actually an image (for non-SVG)
        // This prevents executable files disguised as images
        if ($fileMime !== 'image/svg+xml') {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo === false) {
                throw new \Exception('File is not a valid image');
            }
        }

        // Block dangerous file types even if they pass MIME check
        $dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'pht', 'exe', 'bat', 'cmd', 'com', 'sh', 'html', 'htm', 'js'];
        if (in_array($extension, $dangerousExtensions)) {
            throw new \Exception('This file type is not allowed for security reasons');
        }
    }

    /**
     * Validate document upload (PDF, DOC, etc.)
     *
     * @param UploadedFile $file
     * @param int $maxSizeMB
     * @throws \Exception
     */
    protected function validateDocumentUpload(UploadedFile $file, int $maxSizeMB = 20): void
    {
        // Validate file size
        $maxSizeBytes = $maxSizeMB * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            throw new \Exception("Document size must not exceed {$maxSizeMB}MB");
        }

        // Allowed document MIME types
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Only PDF, DOC, DOCX, XLS, and XLSX documents are allowed');
        }

        // Validate extension
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception('Invalid document extension');
        }
    }
}
