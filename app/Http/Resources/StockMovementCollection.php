<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StockMovementCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            '_links' => [
                'stock_in' => url('/products/{id}/stock/in'),
                'stock_out' => url('/products/{id}/stock/out'),
            ],
        ];
    }
}
