<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\Admin\ShowProducts;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\PaymentOrder;
use App\Http\Livewire\Prueba;
use App\Models\City;
use App\Models\District;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;
use function Symfony\Component\Translation\t;

class PruebaTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_put_correct_information_on_selects()
    {
        $this->createDepartmentCityAndDistrict();
        $cities = City::where('department_id', 1)->get();
        $districts = District::where('city_id', 1)->get();

        $cities2 = City::where('department_id', 2)->get();
        $districts2 = District::where('city_id', 2)->get();

        $cityCount = [];
        foreach ($cities as $city) {
            $cityCount[] = $city;
        }

        $districtCount = [];
        foreach ($districts as $district) {
            $districtCount[] = $district;
        }

        $cityCount2 = [];
        foreach ($cities2 as $city) {
            $cityCount2[] = $city;
        }

        $districtCount2 = [];
        foreach ($districts2 as $district) {
            $districtCount2[] = $district;
        }

       Livewire::test(CreateOrder::class)
            ->set('department_id', 1)
            ->set('city_id', 1)
            ->assertSee($cityCount[0]->name)
            ->assertSee($cityCount[1]->name)
            ->assertSee($districtCount[0]->name)
            ->assertSee($districtCount[1]->name)
            ->assertDontSee($cityCount2[0]->name)
            ->assertDontSee($cityCount2[1]->name);
    }

    /** @test */
    public function it_filters_search_admin() {

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

        Livewire::test(ShowProducts::class)
            ->set('search', 'alejan')
            ->assertSee($producto2->name)
            ->assertSee($producto3->name)
            ->assertDontSee($producto1->name);

    }

    /** @test */
    public function the_cart_destroys_and_redirect_when_create_order()
    {

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $user = $this->createUser();

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);

        $cart = $this->createCart($producto1, 1);

        $this->actingAs($user);

        Livewire::test(CreateOrder::class)
            ->set('contact', 'Contacto')
            ->set('phone', '123456789')
            ->set('envio_type', 1)
            ->set('shipping_cost', 1)
            ->call('create_order')
            ->assertRedirect('/orders/1/payment');

        $this->assertDatabaseCount('orders', 1)
            ->assertDatabaseCount('shoppingcart', 0);
    }

    /** @test */
    public function the_stock_changes_when_order_is_complete()
    {

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $user = $this->createUser();

        $producto1 = $this->createProduct($subcategory, $brand);
        $this->createImages($producto1);


        $originalStock = $producto1;

        Livewire::test('add-cart-item', ['product' => $producto1])
            ->call('addItem');

        $stockPost = DB::table('products')->select('quantity')->get();
        $stockPost = $stockPost[0]->quantity;
        $this->assertNotEquals($originalStock, $stockPost);
    }


}
