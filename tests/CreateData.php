<?php

namespace Tests;

use App\Http\Livewire\CreateOrder;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

trait CreateData
{
    use WithFaker;

    public function generate_product($products = 1, $addToCart = false, $createOrder = false, $color = false, $colorQuantity = 0, $size = false, $productQuantity = 1,  $productStatus = 2, $users = 0) {

        $category = Category::factory()->create();

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => $color,
            'size' => $size,
        ]);

        $brand = Brand::factory()->create();
        $brand->categories()->attach($category->id);

        Product::factory($products)->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => $productQuantity,
            'status' => $productStatus,
        ])->each(function(Product $product){
            Image::factory(4)->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        });

        if ($users != 0) {
            $user = User::factory($users)->create();
        }

        if ($color) {
            $colors =  Color::create([
                'name' => $this->faker->sentence(1),
            ]);

            Product::first()->colors()->attach([
                1 => [
                    'quantity' => $colorQuantity,
                ],
            ]);
        }

        if ($size) {
            Product::first()->sizes()->create([
                'name' => $this->faker->sentence(1),
            ]);

            Color::create([
                'name' => $this->faker->sentence(1),
            ]);

            $sizes = Size::all();

            foreach ($sizes as $size) {
                $size->colors()
                    ->attach([
                        1 => [
                            'quantity' => $colorQuantity,
                        ],
                    ]);
            }
        }

        if($addToCart) {

            $product = Product::first();

            if ($size) {

                $options = [
                    'color_id' => $size->colors->first()->id,
                    'color' => $size->colors->first()->name,
                    'size_id' => $size->id,
                    'size' => $size->name,
                ];
                $options['image'] = Storage::url($product->images->first()->url);

            } else if($color) {

                $options = [
                    'color_id' => $product->colors->first()->id,
                    'color' => $product->colors->first()->name,
                    'size_id' => null,
                ];
                $options['image'] = Storage::url($product->images->first()->url);

            } else {

                $options = [
                    'color_id' => null,
                    'size_id' => null,
                ];
                $options['image'] = Storage::url($product->images->first()->url);

            }

            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $productQuantity,
                'price' => $product->price,
                'weight' => 550,
                'options' => $options,
            ]);

        }

        if($createOrder) {

            $user = User::factory()->create();
            $this->actingAs($user);
            Cart::store($user->id);

        }

    }

    public function generate_search($productName = 'pepe') {

        $category = Category::factory()->create();

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false,
        ]);

        $brand = Brand::factory()->create();
        $brand->categories()->attach($category->id);

        Product::factory()->create([
            'name' => $productName
        ]);
        $this->createImages(Product::first());

        Product::factory()->create();
        $this->createImages(Product::find(2));

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

}
