<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '_links' => [
                'self' => route('categories.show', $this->id),
                'list_categories' => route('categories.index'),
                'update' => route('categories.update', $this->id),
                'delete' => route('categories.destroy', $this->id),
                'products' => route('products.index', ['category' => $this->name]),
            ]
        ];
    }
}
