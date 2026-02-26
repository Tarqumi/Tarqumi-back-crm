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
}
