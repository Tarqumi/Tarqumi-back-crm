<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\RevalidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSettingController extends Controller
{
    public function __construct(
        private RevalidationService $revalidationService
    ) {}

    /**
     * Get all site settings
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::all()->keyBy('key');

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Get a specific setting by key
     */
    public function show(string $key): JsonResponse
    {
        $setting = SiteSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => __('cms.not_found'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $setting,
        ]);
    }

    /**
     * Update or create a setting
     */
    public function upsert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:100',
            'value_ar' => 'nullable|string',
            'value_en' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $setting = SiteSetting::updateOrCreate(
            ['key' => $request->input('key')],
            [
                'value_ar' => $request->input('value_ar'),
                'value_en' => $request->input('value_en'),
            ]
        );

        // Trigger revalidation for all pages since site settings affect all pages
        $this->revalidationService->revalidateAllLandingPages();

        return response()->json([
            'success' => true,
            'data' => $setting,
            'message' => __('cms.setting_updated'),
        ]);
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|max:100',
            'settings.*.value_ar' => 'nullable|string',
            'settings.*.value_en' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updated = [];

        foreach ($request->input('settings') as $settingData) {
            $setting = SiteSetting::updateOrCreate(
                ['key' => $settingData['key']],
                [
                    'value_ar' => $settingData['value_ar'] ?? null,
                    'value_en' => $settingData['value_en'] ?? null,
                ]
            );
            $updated[] = $setting;
        }

        // Trigger revalidation for all pages
        $this->revalidationService->revalidateAllLandingPages();

        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => __('cms.setting_updated'),
        ]);
    }
}
