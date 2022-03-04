<?php

namespace Tests\Feature\Tareas;

use App\Models\ColorProduct;
use App\Models\ColorSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_stock_available_of_a_product(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $this->get('/products/' . $producto1->slug)
            ->assertSee($producto1->name)
            ->assertSee('Stock disponible:')
            ->assertSee($producto1->quantity);
    }

    /** @test */
    public function it_shows_stock_available_of_a_product_with_color(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategoryColor($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);
        $this->createColor($producto1);
        $this->createColor($producto1, 10, 2);

        $quantityAll = ColorProduct::whereHas('product', function(Builder $query) use ($producto1){
            $query->where('id', $producto1->id);
        })->sum('quantity');

        $this->get('/products/' . $producto1->slug)
            ->assertSee($producto1->name)
            ->assertSee('Stock disponible:')
            ->assertSee($quantityAll);
    }

    /** @test */
    public function it_shows_stock_available_of_a_product_with_size(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategorySize($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);
        $this->createSizeAndColor($producto1);
        $this->createSizeAndColor($producto1, 10, 2);

        $quantityAll = ColorSize::whereHas('size.product', function(Builder $query) use ($producto1){
            $query->where('id', $producto1->id);
        })->sum('quantity');

        $this->get('/products/' . $producto1->slug)
            ->assertSee($producto1->name)
            ->assertSee('Stock disponible:')
            ->assertSee($quantityAll);
    }
}
