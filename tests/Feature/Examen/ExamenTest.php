<?php

namespace Tests\Feature\Examen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

}
