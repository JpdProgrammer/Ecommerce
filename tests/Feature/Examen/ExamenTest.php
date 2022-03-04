<?php

namespace Tests\Feature\Examen;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ExamenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_show_stock_available()
    {
        $producto = $this->generate_product(2);

        $this->get('/products/' . $producto[0]->slug)
            ->assertSee($producto[0]->name)
            ->assertSee('Stock disponible:')
            ->assertSee($producto[0]->quantity);
    }

    /** @test */
    public function it_show_stock_available_of_a_product_with_color()
    {
        $producto = $this->generate_product(true);

        $this->get('/products/' . $producto[0]->slug)
            ->assertSee($producto[0]->name)
            ->assertSee('Stock disponible:')
            ->assertSee($producto[0]->quantity);
    }

    /** @test */
    public function it_show_stock_available_of_a_product_with_size()
    {
        $producto = $this->generate_product(true, true);

        $this->get('/products/' . $producto[0]->slug)
            ->assertSee($producto[0]->name)
            ->assertSee('Stock disponible:')
            ->assertSee($producto[0]->quantity);
    }

    /** @test */
    public function it_add_a_product_to_the_cart()
    {
        $producto = $this->add_product_to_cart(1, 1);

        $this->get('/shopping-cart')
            ->assertSee($producto[0]->name)
            ->assertDontSee($producto[1]);
    }

    /** @test */
    public function it_saves_products_on_cart() {

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $user = $this->createUser();

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $producto2 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $producto3= $this->createProduct($subcategory, $brand);
        $this->createImages($producto2);

        $this->actingAs($user);

        Livewire::test('add-cart-item', ['product' => $producto1])
            ->call('addItem');

        Livewire::test('add-cart-item', ['product' => $producto2])
            ->call('addItem');

        $producto1Precio = $producto1->price;
        $producto2Precio = $producto2->price;
        $producto1Cantidad = $producto1->quantity;
        $producto2Cantidad = $producto2->quantity;

        Cart::erase($user->id);
        Cart::store($user->id);

        Cart::destroy();

        Cart::merge($user->id);

        $this->get('/shopping-cart')
            ->assertSee($producto1->name)
            ->assertSee($producto1Cantidad)
            ->assertSee($producto1Precio)
            ->assertSee($producto2->name)
            ->assertSee($producto2Cantidad)
            ->assertSee($producto2Precio)
            ->assertDontSee($producto3->name);

    }

}
