<?php

namespace App\Http\Livewire\Admin;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\ProductFilter;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class ShowProducts2 extends Component
{
    use WithPagination;
    public $search;
    public $per_page = '';
    public $categories;
    public $category = 'default';
    public $subcategories;
    public $subcategory = 'default';
    public $brands;
    public $brand = 'default';
    public $minPrice;
    public $maxPrice;
    public $selectedColumns;
    public $from;
    public $to;
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    public $columns = ['nombre', 'categoria', 'subcategoria', 'marca', 'estado', 'precio', 'stock', 'creado-el', 'colores',
        'tallas'];

    protected $queryString = [
        'category' => ['except' => 'default'],
        'subcategory' => ['except' => 'default'],
        'brand' => ['except' => 'default'],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'sortColumn' => [],
        'sortDirection' => [],
    ];

    public function mount(Request $request)
    {
        $this->categories = Category::orderBy('id')->get();
        $this->subcategories = Subcategory::orderBy('id')->get();
        $this->brands = Brand::orderBy('id')->get();
        $this->selectedColumns = $this->columns;
    }

    protected function getProducts(ProductFilter $productFilter)
    {
        $products = Product::query()
            ->filterBy($productFilter, array_merge(
                [
                    'search' => $this->search,
                    'category' => $this->category,
                    'subcategory' => $this->subcategory,
                    'brand' => $this->brand,
                    'minPrice' => $this->minPrice,
                    'maxPrice' => $this->maxPrice,
                    'from' => $this->from,
                    'to' => $this->to,
                ]
            ))
            ->join('subcategories', 'subcategories.id', 'products.subcategory_id')
            ->select('products.*')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->per_page);

        $products->appends($productFilter->valid());

        return $products;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSubcategory()
    {
        $this->resetPage();
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function updatedCategory($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->brands = Brand::whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        })->get();
        $this->reset(['subcategory', 'brand']);
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render(ProductFilter $productFilter)
    {

        return view('livewire.admin.show-products2', [
            'products' => $this->getProducts($productFilter),
        ])
            ->layout('layouts.admin');
    }
}
