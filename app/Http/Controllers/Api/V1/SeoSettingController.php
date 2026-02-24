<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Services\RevalidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SeoSettingController extends Controller
{
    public function __construct(
        private RevalidationService $revalidationService
    ) {}

    /**
     * Get SEO settings for a specific page
     */
    public function show(string $pageSlug): JsonResponse
    {
        $seoSetting = SeoSetting::where('page_slug', $pageSlug)->first();

        if (!$seoSetting) {
            return response()->json([
                'success' => false,
                'message' => 'SEO settings not found for this page',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $seoSetting,
        ]);
    }

    /**
     * Get all SEO settings
     */
    public function index(): JsonResponse
    {
        $seoSettings = SeoSetting::all();

        return response()->json([
            'success' => true,
            'data' => $seoSettings,
        ]);
    }

    /**
     * Update SEO settings for a page
     */
    public function update(Request $request, string $pageSlug): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'nullable|string|max:60',
            'title_en' => 'nullable|string|max:60',
            'description_ar' => 'nullable|string|max:160',
            'description_en' => 'nullable|string|max:160',
            'keywords_ar' => 'nullable|string|max:255',
            'keywords_en' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $seoSetting = SeoSetting::firstOrCreate(['page_slug' => $pageSlug]);

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($seoSetting->og_image) {
                Storage::disk('public')->delete($seoSetting->og_image);
            }

            $image = $request->file('og_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('seo/og-images', $filename, 'public');
            $seoSetting->og_image = $path;
        }

        $seoSetting->fill($request->except('og_image'));
        $seoSetting->save();

        // Trigger revalidation for the page
        $this->revalidationService->revalidatePath("/ar/{$pageSlug}");
        $this->revalidationService->revalidatePath("/en/{$pageSlug}");

        return response()->json([
            'success' => true,
            'data' => $seoSetting,
            'message' => 'SEO settings updated successfully',
        ]);
    }
}
