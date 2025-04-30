<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\MySql\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OrdersController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the orders.
     *
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get all orders",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Filter orders by phone number",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Filter orders by email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="comment",
     *         in="query",
     *         description="Filter orders by comment",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="flight_num",
     *         in="query",
     *         description="Filter orders by flight number",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_date",
     *         in="query",
     *         description="Filter orders by order date (YYYY-MM-DD format)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="arrival_date",
     *         in="query",
     *         description="Filter orders by arrival date (YYYY-MM-DD format)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Field to sort by (e.g., 'order_date')",
     *         required=false,
     *         @OA\Schema(type="string", default="order_date")
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Sort direction (asc or desc)",
     *         required=false,
     *         @OA\Schema(type="string", default="desc", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of orders",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Order::class);

        // Extract filter parameters from the request
        $filters = [
            'phone' => $request->get('phone', ''),
            'email' => $request->get('email', ''),
            'comment' => $request->get('comment', ''),
            'flight_num' => $request->get('flight_num', ''),
            'order_date' => $request->get('order_date', ''),
            'arrival_date' => $request->get('arrival_date', ''),
        ];

        // Sorting parameters
        $sortBy = $request->get('sort_by', 'order_date');
        $direction = $request->get('direction', 'desc');

        // Get the orders from the service
        $orders = $this->orderService->getAllOrders($filters, $sortBy, $direction);

        // Return the orders as a JSON response
        return response()->json(OrderResource::collection($orders));
    }

    /**
     * Store a new order.
     *
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     *  )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Order::class);

        $validatedData = $request->validate([
            'people_qty' => 'required|integer',
            'order_date' => 'required|string',
            'flight_num' => 'required|string',
            'status'    => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'luggage_qty' => 'required|integer',
            'total' => 'required|decimal',
            'arrival_date' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $order = $this->orderService->createOrder($validatedData);

        return response()->json([
            'message' => 'Order created successfully.',
            'data' => $order
        ], 201);
    }

    /**
     * Show a specific order by id.
     *
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Get a specific order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Transfer not found",
     *          @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        Gate::authorize('view', Order::class);

        $order = $this->orderService->findOrder($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        return response()->json(new OrderResource($order), 200);
    }

    /**
     * Update an order by id.
     *
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     summary="Update an order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *          @OA\Response(
     *          response=404,
     *          description="Order not found",
     *          @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        Gate::authorize('update', Order::class);

        $validatedData = $request->validate([
            'people_qty' => 'required|integer',
            'order_date' => 'required|string',
            'flight_num' => 'required|string',
            'status'    => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'luggage_qty' => 'required|integer',
            'total' => 'required|decimal',
            'arrival_date' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $updated = $this->orderService->updateOrder($validatedData, $id);

        if ($updated) {
            return response()->json([
                'message' => 'Order updated successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Order not found or could not be updated.'
        ], 404);
    }

    /**
     * Remove an order by id.
     *
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     summary="Delete an order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted"
     *     ),
     *          @OA\Response(
     *          response=404,
     *          description="Order not found",
     *          @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function destroy($id): JsonResponse
    {
        Gate::authorize('delete', Order::class);

        $deleted = $this->orderService->deleteOrder($id);

        if ($deleted) {
            return response()->json([
                'message' => 'Order deleted successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Order not found.'
        ], 404);
    }
}
