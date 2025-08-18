<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        $queries = $request->except(['page', 'per_page']);

        return [
            'data' => $this->collection,
            '_links' => [
                'create_product' => route('products.store'),
                'sort_by_name_asc' => route('products.index', $queries + ['sort_by' => 'name', 'order' => 'asc']),
                'sort_by_price_desc' => route('products.index', $queries + ['sort_by' => 'price', 'order' => 'desc']),
                'low_stock' => route('products.index', $queries + ['low_stock' => 20]),
                'search_by_name' => route('products.index', $queries + ['search' => '{search_term}']),
            ],
        ];
    }
}
