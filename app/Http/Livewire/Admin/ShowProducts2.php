<?php

namespace App\Http\Livewire\Admin;


use App\Models\Product;
use App\ProductFilter;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class ShowProducts2 extends Component
{
    use WithPagination;
    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(Request $request)
    {

    }

    protected function getProducts(ProductFilter $productFilter)
    {
        $products = Product::query()
            ->filterBy($productFilter, array_merge(
                [
                    'search' => $this->search,
                ]
            ))
            ->orderByDesc('created_at')
            ->paginate();

        $products->appends($productFilter->valid());

        return $products;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(ProductFilter $productFilter)
    {

        return view('livewire.admin.show-products-2', [
            'products' => $this->getProducts($productFilter),
        ])
            ->layout('layouts.admin');
    }
}
