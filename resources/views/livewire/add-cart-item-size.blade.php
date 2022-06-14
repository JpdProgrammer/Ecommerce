<div x-data>
    <div class="mt-2">
        <p class="text-xl text-gray-700">Talla:</p>
        <select dusk="sizeSelect" wire:model="size_id" class="form-control w-full">
            <option value="" selected disabled>Seleccione una talla</option>
            @foreach ($sizes as $size)
                <option dusk="size_{{ $size->id }}" value="{{$size->id}}">{{$size->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-2">
        <p class="text-xl text-gray-700">Color:</p>
        <select dusk="colorSelect" wire:model="color_id" class="form-control w-full">
            <option value="" selected disabled>Seleccione un color</option>
            @foreach ($colors as $color)
                <option dusk="color_{{ $color->id }}" value="{{$color->id}}">{{ __(ucfirst($color->name)) }}</option>
            @endforeach
        </select>
    </div>

    <p class="text-gray-700 my-4">
        <span class="font-semibold text-lg">Stock disponible:</span>
        @if($quantity)
            {{ $quantity }}
        @else
            {{ $product->stock }}
        @endif
    </p>

    <div class="flex">
        <div class="mr-4">
            <x-jet-secondary-button
                dusk="subtractItemButton"
                disabled
                x-bind:disabled="$wire.qty <= 1"
                wire:loading.attr="disabled"
                wire:target="decrement"
                wire:click="decrement">
                -
            </x-jet-secondary-button>
            <span class="mx-2 text-gray-700">{{ $qty }}</span>
            <x-jet-secondary-button
                dusk="addItemButton"
                x-bind:disabled="$wire.qty >= $wire.quantity"
                wire:loading.attr="disabled"
                wire:target="increment"
                wire:click="increment">
                +
            </x-jet-secondary-button>
        </div>
        <div class="flex-1">
            <x-button
                dusk="addToCartBtn"
                x-bind:disabled="$wire.qty > $wire.quantity"
                x-bind:disabled="!$wire.quantity"
                wire:click="addItem"
                wire:loading.attr="disabled"
                wire:target="addItem"
                class="w-full" color="orange">
                Agregar al carrito de compras
            </x-button>
        </div>
    </div>
</div>
