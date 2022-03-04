<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\Department;
use App\Models\District;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;

trait TestHelper
{
    use WithFaker;

    public function createCategory()
    {
        return Category::factory()->create();
    }

    public function createSubcategory($category)
    {
        return Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);
    }

    public function createSubcategoryColor($category)
    {
        return Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => true,
        ]);
    }

    public function createSubcategorySize($category)
    {
        return Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => true,
            'size' => true,
        ]);
    }

    public function createBrand($category)
    {
        $brand = Brand::factory()->create();
        $brand->categories()->attach($category->id);
        return $brand;
    }

    public function createProduct($subcategory, $brand)
    {
        return Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);
    }

    public function createImages($product)
    {
        Image::factory(4)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);
    }

    public function createUser()
    {
        return User::factory()->create();
    }

    public function createCart($product, $qty)
    {
        $options = [
            'color_id' => null,
            'size_id' => null,
        ];
        $options['image'] = Storage::url($product->images->first()->url);

        return Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'weight' => 550,
            'options' => $options,
        ]);

    }

    public function createCartColor($product, $qty)
    {
        $options = [
            'color_id' => $product->colors->first()->id,
            'color' => $product->colors->first()->name,
            'size_id' => null,
        ];
        $options['image'] = Storage::url($product->images->first()->url);

        return Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'weight' => 550,
            'options' => $options,
        ]);
    }

    public function createCartSize($product, $qty)
    {
        $size = $product->sizes->first();

        $options = [
            'color_id' => $size->colors->first()->id,
            'color' => $size->colors->first()->name,
            'size_id' => $size->id,
            'size' => $size->name,
        ];
        $options['image'] = Storage::url($product->images->first()->url);

        return Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'weight' => 550,
            'options' => $options,
        ]);

    }

    public function createColor($product, $quantity = 10, $idC = 1)
    {
        Color::create([
            'name' => $this->faker->sentence(1),
        ]);

        $product->colors()->attach([
            $idC => [
                'quantity' => $quantity,
            ],
        ]);
    }


    public function createSizeAndColor($product , $quantity = 10, $idC = 1)
    {
        $product->sizes()->create([
            'name' => $this->faker->sentence(1),
        ]);

        Color::create([
            'name' => $this->faker->sentence(1),
        ]);

        $sizes = Size::all();

        foreach ($sizes as $size) {
            $size->colors()
                ->attach([
                    $idC => [
                        'quantity' => $quantity,
                    ],
                ]);
        }
    }

    public function createOrder($user)
    {
        $order = new Order();

        $order->user_id = $user->id;
        $order->contact = 'Contacto';
        $order->phone = '123456789';
        $order->envio_type = 1;
        $order->shipping_cost = 1;
        $order->total = 1;
        $order->content = Cart::content();

        $order->save();

        return $order;
    }

    public function createDepartmentCityAndDistrict()
    {
        Department::factory(8)->create()->each(function (Department $department) {
            City::factory(8)->create([
                'department_id' => $department->id
            ])->each(function (City $city) {
                    District::factory(8)->create([
                    'city_id' => $city->id
                ]);
            });
        });
    }

    function quantity($product_id, $color_id = null, $size_id = null)
    {
        $product = Product::find($product_id);
        If ($size_id) {
            $size = Size::find($size_id);
            $quantity = $size->colors->find($color_id)->pivot->quantity;
        } elseif ($color_id) {
            $quantity = $product->colors->find($color_id)->pivot->quantity;
        } else {
            $quantity = $product->quantity;
        }
        return $quantity;
    }

    function qty_added($product_id, $color_id = null, $size_id = null)
    {
        $cart = Cart::content();
        $item = $cart->where('id', $product_id)
            ->where('options.color_id', $color_id)
            ->where('options.size_id', $size_id)
            ->first();
        if ($item) {
            return $item->qty;
        } else {
            return 0;
        }
    }

    function qty_available($product_id, $color_id = null, $size_id = null){
        return quantity($product_id, $color_id, $size_id) - qty_added($product_id, $color_id, $size_id);
    }

}
