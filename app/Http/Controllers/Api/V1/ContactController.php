<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactSubmissionRequest;
use App\Http\Requests\IndexContactSubmissionRequest;
use App\Http\Resources\ContactSubmissionResource;
use App\Models\ContactSubmission;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService
    ) {}

    /**
     * Submit contact form (Public endpoint with rate limiting)
     */
    public function submit(StoreContactSubmissionRequest $request): JsonResponse
    {
        try {
            $submission = $this->contactService->submitContactForm($request->validated());

            return response()->json([
                'success' => true,
                'message' => __('contact.submitted'),
                'data' => [
                    'id' => $submission->id,
                    'submitted_at' => $submission->submitted_at->toIso8601String(),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit contact form. Please try again later.',
            ], 500);
        }
    }

    /**
     * List contact submissions (Admin only)
     */
    public function index(IndexContactSubmissionRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $submissions = $this->contactService->getSubmissions($filters);

        return response()->json([
            'success' => true,
            'data' => ContactSubmissionResource::collection($submissions),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'per_page' => $submissions->perPage(),
                'total' => $submissions->total(),
                'from' => $submissions->firstItem(),
                'to' => $submissions->lastItem(),
            ],
        ]);
    }

    /**
     * Show single submission (Admin only)
     */
    public function show(ContactSubmission $contactSubmission): JsonResponse
    {
        // Auto-mark as read
        if ($contactSubmission->status === 'new') {
            $contactSubmission->markAsRead(auth()->id());
        }

        $contactSubmission->load('reader');

        return response()->json([
            'success' => true,
            'data' => new ContactSubmissionResource($contactSubmission),
        ]);
    }

    /**
     * Update submission status (Admin only)
     */
    public function updateStatus(Request $request, ContactSubmission $contactSubmission): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:new,read,replied,archived,spam'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $contactSubmission->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes ?? $contactSubmission->admin_notes,
            ]);

            return response()->json([
                'success' => true,
                'data' => new ContactSubmissionResource($contactSubmission->fresh()),
                'message' => __('contact.status_updated'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark as spam (Admin only)
     */
    public function markAsSpam(ContactSubmission $contactSubmission): JsonResponse
    {
        try {
            $this->contactService->markAsSpam($contactSubmission);

            return response()->json([
                'success' => true,
                'message' => __('contact.marked_spam'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as spam: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete submission (Admin only)
     */
    public function destroy(ContactSubmission $contactSubmission): JsonResponse
    {
        try {
            $contactSubmission->delete();

            return response()->json([
                'success' => true,
                'message' => __('contact.deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete submission: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get statistics (Admin only)
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->contactService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
