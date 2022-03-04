<?php

namespace Tests\Feature\Tareas;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function no_logged_users_cant_create_a_order()
    {
        $this->get('/orders/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function logged_users_can_create_a_order()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $user = $this->createUser();

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $this->createCart($producto1, 1);
        $this->createOrder($user);

        $this->actingAs($user);

        $this->get('/orders/create')
            ->assertSee($producto1->name);
    }

    /** @test */
    public function it_changes_the_stock_when_complete_the_order()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $user = $this->createUser();

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $this->createCart($producto1, 1);
        $this->createOrder($user);

        $producto1->quantity = $this->qty_available($producto1->id);

        $this->get('/products/' . $producto1->slug)
            ->assertSee($producto1->name)
            ->assertSee('Stock disponible:')
            ->assertSee($producto1->quantity);
    }
}
