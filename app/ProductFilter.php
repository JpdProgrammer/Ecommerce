<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductFilter extends QueryFilter
{

    public function rules(): array
    {
        return [
            'search' => 'filled',
        ];
    }

    public function search($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%");
    }

    public function category($query, $category)
    {
        return $query->where(function ($query) use ($category) {
            return $query->whereHas('subcategory', function ($query) use ($category) {
                return $query->where('subcategories.category_id', $category);
            });
        });
    }
}
