<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Transfer",
 *     type="object",
 *     title="Transfer",
 *     description="Transfer model",
 *     required={"driver_id", "people_qty", "status", "pickup", "unload", "destination"},
 *     @OA\Property(
 *         property="id",
 *         description="ID of the transfer",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="driver_id",
 *         description="ID of the driver associated with the transfer",
 *         type="integer",
 *         example=123
 *     ),
 *     @OA\Property(
 *         property="luggage",
 *         description="Details about the luggage",
 *         type="string",
 *         example="2 suitcases"
 *     ),
 *     @OA\Property(
 *         property="people_qty",
 *         description="Number of people in the transfer",
 *         type="integer",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="flight_num",
 *         description="Flight number for the transfer",
 *         type="string",
 *         example="AB1234"
 *     ),
 *     @OA\Property(
 *         property="pickup",
 *         description="Pickup location",
 *         type="string",
 *         example="Airport Terminal 1"
 *     ),
 *     @OA\Property(
 *         property="unload",
 *         description="Unload location",
 *         type="string",
 *         example="Hotel XYZ"
 *     ),
 *     @OA\Property(
 *         property="gate",
 *         description="Gate number at the pickup location",
 *         type="string",
 *         example="Gate A"
 *     ),
 *     @OA\Property(
 *         property="destination",
 *         description="Transfer destination",
 *         type="string",
 *         example="City Center"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         description="Current status of the transfer",
 *         type="string",
 *         example="completed"
 *     ),
 *     @OA\Property(
 *         property="comment",
 *         description="Additional comments for the transfer",
 *         type="string",
 *         example="VIP transfer"
 *     )
 * )
 */
class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'luggage' => $this->luggage,
            'people_qty' => $this->people_qty,
            'flight_num' => $this->flight_num,
            'pickup' => $this->pickup,
            'unload' => $this->unload,
            'gate' => $this->gate,
            'destination' => $this->destination,
            'status' => $this->status,
            'comment' => $this->comment,
        ];
    }
}
