<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use App\Services\RevalidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialLinkController extends Controller
{
    public function __construct(
        private RevalidationService $revalidationService
    ) {}

    /**
     * Get all social links (public endpoint)
     */
    public function index(): JsonResponse
    {
        $links = SocialLink::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $links,
        ]);
    }

    /**
     * Get all social links including inactive (admin endpoint)
     */
    public function adminIndex(): JsonResponse
    {
        $links = SocialLink::ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $links,
        ]);
    }

    /**
     * Create a new social link
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|in:facebook,twitter,instagram,linkedin,youtube,tiktok,github,whatsapp,telegram',
            'url' => 'required|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if platform already exists
        $exists = SocialLink::where('platform', $request->input('platform'))->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => __('cms.social_link_exists'),
            ], 422);
        }

        $link = SocialLink::create($request->all());

        // Trigger revalidation
        $this->revalidationService->revalidateAllLandingPages();

        return response()->json([
            'success' => true,
            'data' => $link,
            'message' => __('cms.social_link_created'),
        ], 201);
    }

    /**
     * Update a social link
     */
    public function update(Request $request, SocialLink $socialLink): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $socialLink->update($request->all());

        // Trigger revalidation
        $this->revalidationService->revalidateAllLandingPages();

        return response()->json([
            'success' => true,
            'data' => $socialLink,
            'message' => __('cms.social_link_updated'),
        ]);
    }

    /**
     * Delete a social link
     */
    public function destroy(SocialLink $socialLink): JsonResponse
    {
        $socialLink->delete();

        // Trigger revalidation
        $this->revalidationService->revalidateAllLandingPages();

        return response()->json([
            'success' => true,
            'message' => __('cms.social_link_deleted'),
        ]);
    }

    /**
     * Reorder social links
     */
    public function reorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'links' => 'required|array',
            'links.*.id' => 'required|exists:social_links,id',
            'links.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        foreach ($request->input('links') as $linkData) {
            SocialLink::where('id', $linkData['id'])
                ->update(['order' => $linkData['order']]);
        }

        // Trigger revalidation
        $this->revalidationService->revalidateAllLandingPages();

        return response()->json([
            'success' => true,
            'message' => __('cms.social_link_updated'),
        ]);
    }
}
