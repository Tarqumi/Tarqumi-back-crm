<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactSubmissionRequest;
use App\Http\Resources\ContactSubmissionResource;
use App\Models\ContactSubmission;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(
        protected ContactService $contactService
    ) {}

    /**
     * Submit contact form (public endpoint)
     */
    public function submit(StoreContactSubmissionRequest $request): JsonResponse
    {
        $submission = $this->contactService->submitContactForm(
            $request->validated(),
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.',
            'data' => new ContactSubmissionResource($submission),
        ], 201);
    }

    /**
     * Get all submissions (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ContactSubmission::class);

        $filters = $request->only(['status', 'search', 'date_from', 'date_to', 'language', 'subject']);
        $perPage = $request->input('per_page', 25);

        $submissions = $this->contactService->getSubmissions($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => ContactSubmissionResource::collection($submissions->items()),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'per_page' => $submissions->perPage(),
                'total' => $submissions->total(),
                'last_page' => $submissions->lastPage(),
            ],
            'links' => [
                'first' => $submissions->url(1),
                'last' => $submissions->url($submissions->lastPage()),
                'prev' => $submissions->previousPageUrl(),
                'next' => $submissions->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Get single submission (admin only)
     */
    public function show(ContactSubmission $submission): JsonResponse
    {
        $this->authorize('view', $submission);

        // Auto-mark as read
        if ($submission->status === 'new') {
            $submission->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'data' => new ContactSubmissionResource($submission),
        ]);
    }

    /**
     * Update submission status (admin only)
     */
    public function updateStatus(Request $request, ContactSubmission $submission): JsonResponse
    {
        $this->authorize('update', $submission);

        $request->validate([
            'status' => ['required', 'in:new,read,replied,archived,spam'],
        ]);

        $updated = $this->contactService->updateStatus($submission, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => new ContactSubmissionResource($updated),
        ]);
    }

    /**
     * Delete submission (admin only)
     */
    public function destroy(ContactSubmission $submission): JsonResponse
    {
        $this->authorize('delete', $submission);

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Submission deleted successfully',
        ]);
    }

    /**
     * Bulk update status (admin only)
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ContactSubmission::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:contact_submissions,id'],
            'status' => ['required', 'in:new,read,replied,archived,spam'],
        ]);

        $count = $this->contactService->bulkUpdateStatus($request->ids, $request->status);

        return response()->json([
            'success' => true,
            'message' => "{$count} submissions updated successfully",
        ]);
    }

    /**
     * Bulk delete (admin only)
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ContactSubmission::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:contact_submissions,id'],
        ]);

        $count = $this->contactService->bulkDelete($request->ids);

        return response()->json([
            'success' => true,
            'message' => "{$count} submissions deleted successfully",
        ]);
    }

    /**
     * Export submissions (admin only)
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', ContactSubmission::class);

        $filters = $request->only(['status', 'search', 'date_from', 'date_to', 'language', 'subject']);
        $submissions = $this->contactService->getSubmissions($filters, 10000)->items();

        $filename = 'contact-submissions-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Language', 'IP Address', 'Created At']);

            // Data
            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->id,
                    $submission->name,
                    $submission->email,
                    $submission->phone,
                    $submission->subject,
                    $submission->message,
                    $submission->status,
                    $submission->language,
                    $submission->ip_address,
                    $submission->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
