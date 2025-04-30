<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransferResource;
use App\Models\MySql\Transfer;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TransfersController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * @OA\Get(
     *     path="/api/transfers",
     *     summary="Get a list of transfers",
     *     description="Retrieve a list of transfers, with optional filtering by driver_id, status, flight_num, and sorting by id, status, pickup, and unload.",
     *     tags={"Transfers"},
     *     @OA\Parameter(
     *         name="driver_id",
     *         in="query",
     *         required=false,
     *         description="Filter by driver ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter by status",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="flight_num",
     *         in="query",
     *         required=false,
     *         description="Filter by flight number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_field",
     *         in="query",
     *         required=false,
     *         description="Field to sort by (e.g., id, status, pickup, unload)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         required=false,
     *         description="Sort direction (asc or desc)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of transfers retrieved successfully.",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Transfer"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Transfer::class);

        $filters = $request->only(['driver_id', 'status', 'flight_num']);
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'asc');
        $transfers = $this->transferService->getAllTransfers($filters, $sortField, $sortDirection);

        return response()->json($transfers, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/transfers",
     *     summary="Create a new transfer",
     *     description="Create a new transfer record with the provided data.",
     *     tags={"Transfers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Transfer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transfer created successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Transfer")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Transfer::class);

        $validatedData = $request->validate([
            'driver_id' => 'required|integer',
            'luggage' => 'nullable|string',
            'people_qty' => 'required|integer',
            'flight_num' => 'nullable|string',
            'pickup' => 'required|string',
            'unload' => 'required|string',
            'gate' => 'nullable|string',
            'destination' => 'required|string',
            'status' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $transfer = $this->transferService->createTransfer($validatedData);

        return response()->json([
            'message' => 'Transfer created successfully.',
            'data' => $transfer
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/transfers/{id}",
     *     summary="Get a specific transfer",
     *     description="Retrieve a specific transfer by its ID.",
     *     tags={"Transfers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transfer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transfer retrieved successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Transfer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transfer not found",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        Gate::authorize('view', Transfer::class);

        $transfer = $this->transferService->findTransfer($id);

        if (!$transfer) {
            return response()->json([
                'message' => 'Transfer not found.'
            ], 404);
        }

        // Use the TransferResource to return the transfer data
        return response()->json(new TransferResource($transfer), 200);
    }


    /**
     * @OA\Put(
     *     path="/api/transfers/{id}",
     *     summary="Update a specific transfer",
     *     description="Update the specified transfer by its ID.",
     *     tags={"Transfers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transfer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Transfer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transfer updated successfully.",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transfer not found",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        Gate::authorize('update', Transfer::class);

        $validatedData = $request->validate([
            'driver_id' => 'required|integer',
            'luggage' => 'nullable|string',
            'people_qty' => 'required|integer',
            'flight_num' => 'required|string',
            'pickup' => 'nullable|string',
            'unload' => 'nullable|string',
            'gate' => 'nullable|string',
            'destination' => 'nullable|string',
            'status' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $updated = $this->transferService->updateTransfer($validatedData, $id);

        if ($updated) {
            return response()->json([
                'message' => 'Transfer updated successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Transfer not found or could not be updated.'
        ], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/transfers/{id}",
     *     summary="Delete a specific transfer",
     *     description="Delete the specified transfer by its ID.",
     *     tags={"Transfers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transfer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transfer deleted successfully.",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transfer not found",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        Gate::authorize('delete', Transfer::class);

        $deleted = $this->transferService->deleteTransfer($id);

        if ($deleted) {
            return response()->json([
                'message' => 'Transfer deleted successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Transfer not found.'
        ], 404);
    }
}
