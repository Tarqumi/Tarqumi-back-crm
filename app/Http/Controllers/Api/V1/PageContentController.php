<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Services\RevalidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageContentController extends Controller
{
    public function __construct(
        private RevalidationService $revalidationService
    ) {}

    /**
     * Get all content blocks for a specific page
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_slug' => 'required|string|in:home,about,services,projects,blog,contact',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pageSlug = $request->input('page_slug');
        $contents = PageContent::where('page_slug', $pageSlug)->get();

        return response()->json([
            'success' => true,
            'data' => $contents,
        ]);
    }

    /**
     * Update or create a content block
     */
    public function upsert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_slug' => 'required|string|in:home,about,services,projects,blog,contact',
            'section_key' => 'required|string|max:100',
            'value_ar' => 'nullable|string',
            'value_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $content = PageContent::firstOrNew([
            'page_slug' => $request->input('page_slug'),
            'section_key' => $request->input('section_key'),
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('page-content', $filename, 'public');
            $content->image = $path;
        }

        $content->value_ar = $request->input('value_ar');
        $content->value_en = $request->input('value_en');
        $content->save();

        // Trigger revalidation
        $pageSlug = $request->input('page_slug');
        $this->revalidationService->revalidatePath("/ar/{$pageSlug}");
        $this->revalidationService->revalidatePath("/en/{$pageSlug}");

        return response()->json([
            'success' => true,
            'data' => $content,
            'message' => 'Content updated successfully',
        ]);
    }

    /**
     * Bulk update content blocks
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_slug' => 'required|string|in:home,about,services,projects,blog,contact',
            'contents' => 'required|array',
            'contents.*.section_key' => 'required|string|max:100',
            'contents.*.value_ar' => 'nullable|string',
            'contents.*.value_en' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pageSlug = $request->input('page_slug');
        $contents = $request->input('contents');
        $updated = [];

        foreach ($contents as $contentData) {
            $content = PageContent::updateOrCreate(
                [
                    'page_slug' => $pageSlug,
                    'section_key' => $contentData['section_key'],
                ],
                [
                    'value_ar' => $contentData['value_ar'] ?? null,
                    'value_en' => $contentData['value_en'] ?? null,
                ]
            );
            $updated[] = $content;
        }

        // Trigger revalidation
        $this->revalidationService->revalidatePath("/ar/{$pageSlug}");
        $this->revalidationService->revalidatePath("/en/{$pageSlug}");

        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Contents updated successfully',
        ]);
    }
}
