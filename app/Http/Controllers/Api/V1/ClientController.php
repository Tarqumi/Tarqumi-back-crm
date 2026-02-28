<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(
        private ClientService $clientService
    ) {}

    public function index(IndexClientRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $clients = $this->clientService->getClients($filters);

        return response()->json([
            'success' => true,
            'data' => ClientResource::collection($clients),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'total' => $clients->total(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ],
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        try {
            $client = $this->clientService->createClient($request->validated());

            return response()->json([
                'success' => true,
                'data' => new ClientResource($client),
                'message' => __('client.created'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create client: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Client $client): JsonResponse
    {
        $client->loadCount('projects');
        $client->load(['creator', 'updater']);

        return response()->json([
            'success' => true,
            'data' => new ClientResource($client),
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        try {
            $updatedClient = $this->clientService->updateClient($client, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new ClientResource($updatedClient),
                'message' => __('client.updated'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update client: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Client $client): JsonResponse
    {
        try {
            $this->clientService->deleteClient($client);

            return response()->json([
                'success' => true,
                'message' => __('client.deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function restore(Client $client): JsonResponse
    {
        try {
            $client->restore();

            return response()->json([
                'success' => true,
                'data' => new ClientResource($client->fresh()),
                'message' => __('client.restored'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore client: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(Client $client): JsonResponse
    {
        try {
            $isActive = request()->input('is_active');
            
            // Prevent deactivating default client
            if ($client->is_default && !$isActive) {
                return response()->json([
                    'success' => false,
                    'message' => 'Default client must remain active',
                ], 400);
            }

            $client->update([
                'is_active' => $isActive,
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'data' => new ClientResource($client->fresh()),
                'message' => $isActive ? 'Client activated' : 'Client deactivated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function export(IndexClientRequest $request)
    {
        try {
            $filters = $request->validated();
            $clients = $this->clientService->getClientsForExport($filters);

            $filename = 'clients-' . now()->format('Y-m-d-His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($clients) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($file, [
                    'ID',
                    'Name',
                    'Company',
                    'Email',
                    'Phone',
                    'WhatsApp',
                    'Website',
                    'Industry',
                    'Address',
                    'Status',
                    'Projects Count',
                    'Created At',
                ]);

                // Data rows
                foreach ($clients as $client) {
                    fputcsv($file, [
                        $client->id,
                        $client->name,
                        $client->company_name ?? '',
                        $client->email,
                        $client->phone ?? '',
                        $client->whatsapp ?? '',
                        $client->website ?? '',
                        $client->industry ?? '',
                        $client->address ?? '',
                        $client->is_active ? 'Active' : 'Inactive',
                        $client->projects_count ?? 0,
                        $client->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export clients: ' . $e->getMessage(),
            ], 500);
        }
    }
}
