<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ServiceManagementService
{
    public function __construct(
        private RevalidationService $revalidationService
    ) {}

    public function createService(array $data): Service
    {
        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        // Set created_by and updated_by
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        // Set default order if not provided
        if (!isset($data['order'])) {
            $data['order'] = Service::max('order') + 1;
        }

        $service = Service::create($data);

        // Trigger revalidation
        $this->revalidationService->revalidatePath('/ar/services');
        $this->revalidationService->revalidatePath('/en/services');
        $this->revalidationService->revalidatePath('/ar');
        $this->revalidationService->revalidatePath('/en');

        return $service;
    }

    public function updateService(Service $service, array $data): Service
    {
        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        // Set updated_by
        $data['updated_by'] = Auth::id();

        $service->update($data);

        // Trigger revalidation
        $this->revalidationService->revalidatePath('/ar/services');
        $this->revalidationService->revalidatePath('/en/services');
        $this->revalidationService->revalidatePath('/ar');
        $this->revalidationService->revalidatePath('/en');

        return $service->fresh();
    }

    public function reorderServices(array $services): void
    {
        foreach ($services as $serviceData) {
            Service::where('id', $serviceData['id'])
                ->update(['order' => $serviceData['order']]);
        }

        // Trigger revalidation
        $this->revalidationService->revalidatePath('/ar/services');
        $this->revalidationService->revalidatePath('/en/services');
        $this->revalidationService->revalidatePath('/ar');
        $this->revalidationService->revalidatePath('/en');
    }

    private function uploadImage(UploadedFile $file): string
    {
        // Validate file
        $this->validateImage($file);

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store in public disk
        $path = $file->storeAs('services', $filename, 'public');

        return $path;
    }

    private function validateImage(UploadedFile $file): void
    {
        // Max 20MB
        if ($file->getSize() > 20 * 1024 * 1024) {
            throw new \Exception('Image size must not exceed 20MB');
        }

        // Only images
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Only JPEG, PNG, GIF, WebP, and SVG images are allowed');
        }
    }
}
