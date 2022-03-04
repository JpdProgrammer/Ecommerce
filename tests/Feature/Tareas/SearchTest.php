<?php

namespace Tests\Feature\Tareas;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_filters() {

        $category = $this->createCategory();
        $subcategory = $this->createSubcategorySize($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = Product::factory()->create([
            'name' => 'alejandroMagno',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);
        $this->createImages($producto2);

        $producto3 = Product::factory()->create([
            'name' => 'soyalejandro',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);
        $this->createImages($producto3);

        $this->get('/search?name=alejandro')
            ->assertSee($producto2->name)
            ->assertSee($producto3->name)
            ->assertDontSee($producto1->name);

    }

    /** @test */
    public function it_filters_empty() {

        $category = $this->createCategory();
        $subcategory = $this->createSubcategorySize($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $producto3 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto3);

        $this->get('/search?name=')
            ->assertSee($producto2->name)
            ->assertSee($producto3->name)
            ->assertSee($producto1->name);

    }

}
