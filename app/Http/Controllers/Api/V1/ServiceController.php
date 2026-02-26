<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceManagementService $serviceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Service::query();

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by show_on_home
        if ($request->has('show_on_home')) {
            $query->where('show_on_home', $request->boolean('show_on_home'));
        }

        $services = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => ServiceResource::collection($services),
        ]);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $service = $this->serviceService->createService($request->validated());

            return response()->json([
                'success' => true,
                'data' => new ServiceResource($service),
                'message' => __('cms.service_created'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Service $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ServiceResource($service),
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        try {
            $updatedService = $this->serviceService->updateService($service, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new ServiceResource($updatedService),
                'message' => __('cms.service_updated'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Service $service): JsonResponse
    {
        try {
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => __('cms.service_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reorder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'services' => ['required', 'array'],
                'services.*.id' => ['required', 'exists:services,id'],
                'services.*.order' => ['required', 'integer', 'min:0'],
            ]);

            $this->serviceService->reorderServices($request->input('services'));

            return response()->json([
                'success' => true,
                'message' => 'Services reordered successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder services: ' . $e->getMessage(),
            ], 500);
        }
    }
}
