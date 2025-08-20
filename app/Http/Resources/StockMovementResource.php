<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '_links' => [
                'self' => route('stock-movements.show', $this->id),
                'list_movements' => route('stock-movements.index'),
                'related_product' => route('products.show', $this->product_id),
                'undo_movement' => route('stock-movements.destroy', $this->id),
            ]
        ];
    }
}
