<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     description="Order model",
 *     required={"people_qty", "order_date", "flight_num", "status", "phone", "email", "total", "arrival_date"},
 *     @OA\Property(
 *         property="id",
 *         description="ID of the order",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="people_qty",
 *         description="Number of people in the order",
 *         type="integer",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="order_date",
 *         description="Order date and time",
 *         type="string",
 *         format="date-time",
 *         example="2024-10-10T10:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="flight_num",
 *         description="Flight number associated with the order",
 *         type="string",
 *         example="AB1234"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         description="Order status",
 *         type="string",
 *         example="completed"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         description="Phone number of the person who placed the order",
 *         type="string",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         description="Email address of the person who placed the order",
 *         type="string",
 *         format="email",
 *         example="email@example.com"
 *     ),
 *     @OA\Property(
 *         property="luggage_qty",
 *         description="Quantity of luggage for the order",
 *         type="integer",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="total",
 *         description="Total price of the order",
 *         type="number",
 *         format="float",
 *         example=120.50
 *     ),
 *     @OA\Property(
 *         property="arrival_date",
 *         description="Arrival date and time",
 *         type="string",
 *         format="date-time",
 *         example="2024-10-11T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="comment",
 *         description="Additional comments for the order",
 *         type="string",
 *         example="Please arrange for a VIP transfer."
 *     )
 * )
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'people_qty' => $this->people_qty,
            'order_date' => $this->order_date->toIso8601String(),
            'flight_num' => $this->flight_num,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'luggage_qty' => $this->luggage_qty,
            'total' => $this->total,
            'arrival_date' => $this->arrival_date->toIso8601String(),
            'comment' => $this->comment,
        ];
    }
}
