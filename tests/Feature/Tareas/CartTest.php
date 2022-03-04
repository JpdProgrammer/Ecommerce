<?php

namespace Tests\Feature\Tareas;

use App\Models\ColorProduct;
use App\Models\ColorSize;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_add_a_product_to_the_cart(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $this->createCart($producto1, 1);

        $this->get('/shopping-cart')
                ->assertSee($producto1->name)
                ->assertDontSee($producto2->name);
    }

    /** @test */
    public function it_add_a_product_with_color_to_the_cart(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategoryColor($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $this->createColor($producto1);

        $this->createCartColor($producto1, 1);

        $this->get('/shopping-cart')
            ->assertSee($producto1->name)
            ->assertSee('Color: ' . $producto1->colors->first()->name)
            ->assertDontSee($producto2->name);
    }

    /** @test */
    public function it_add_a_product_with_size_to_the_cart(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategorySize($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $this->createSizeAndColor($producto1);

        $this->createCartSize($producto1, 1);

        $this->get('/shopping-cart')
            ->assertSee($producto1->name)
            ->assertSee($producto1->sizes->first()->name)
            ->assertDontSee($producto2->name);
    }

    /** @test */
    public function it_shows_all_products_added_to_the_cart(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $producto3 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto3);

        $this->createCart($producto1, 1);
        $this->createCart($producto2, 1);

        $this->get('/shopping-cart')
            ->assertSee($producto1->name)
            ->assertSee($producto2->name)
            ->assertDontSee($producto3->name);
    }

    /** @test */
    public function it_remove_the_cart(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $this->createCart($producto1, 1);
        $this->createCart($producto2, 1);

        Cart::destroy();

        $this->get('/shopping-cart')
            ->assertSee('TU CARRITO DE COMPRAS ESTÁ VACÍO')
            ->assertDontSee($producto1->name)
            ->assertDontSee($producto2->name);
    }

    /** @test */
    public function it_remove_a_product_of_the_cart(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $producto3 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto3);

        $this->createCart($producto1, 1);
        $this->createCart($producto2, 1);

        $rowIds = [];
        foreach (Cart::content() as $item) {
            $rowIds[] = $item->rowId;
        }

        Cart::remove($rowIds[0]);

        $this->get('/shopping-cart')
            ->assertDontSee($producto1->name)
            ->assertSee($producto2->name);
    }

    /** @test */
    public function red_circle_changes(){
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $producto3 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto3);

        $this->createCart($producto1, 1);
        $this->createCart($producto2, 1);

        $this->get('/')
            ->assertSee(Cart::count());
    }

}
