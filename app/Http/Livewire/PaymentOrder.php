<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentOrder extends Component
{
    use AuthorizesRequests;

    public $order;

    protected $listeners = ['payOrder'];

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function payOrder()
    {
        $this->order->status = 2;

        $this->productSold();

        $this->order->save();

        return redirect()->route('orders.show', $this->order);
    }

    public function productSold()
    {
        $items = json_decode($this->order->content);


        foreach ($items as $item) {
            $product = Product::find($item->id);
            $product->sold = $item->qty +  $product->sold;
            $product->save();
        }
    }

    public function render()
    {
        $this->authorize('view', $this->order);
        $this->authorize('payment', $this->order);

        $items = json_decode($this->order->content);
        $envio = json_decode($this->order->envio);

        return view('livewire.payment-order', compact('items', 'envio'));
    }
}
