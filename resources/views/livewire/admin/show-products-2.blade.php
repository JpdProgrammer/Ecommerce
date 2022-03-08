<div>
    <div>
        <x-slot name="header">
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                    Lista de productos
                </h2>

                <x-button-link class="ml-auto" href="{{ route('admin.products.create') }}">
                    Agregar producto
                </x-button-link>
            </div>
        </x-slot>
        <!-- This example requires Tailwind CSS v2.0+ -->
        <x-table-responsive>

            <div class="px-6 py-4">
                <x-jet-input class="w-1/4"
                             wire:model="search"
                             type="text"
                             placeholder="Introduzca el nombre del producto a buscar" />
            </div>

            @if($products->count())
                <table class="min-w-full overflow-x-auto divide-y divide-gray-200 block whitespace-nowrap">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subategoría
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Marca
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Precio
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Creado el:
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Colores
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tallas
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $product->images->count() ? Storage::url($product->images->first()->url) :
                                'img/default.jpg'}}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->subcategory->category->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $product->subcategory->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $product->brand->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 1 ? 'red' : 'green'
                        }}-100 text-{{ $product->status == 1 ? 'red' : 'green'}}-800">
                            {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                        </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->price }}&euro;
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->created_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($product->colors->count())
                                    @foreach($product->colors as $color)
                                        {{ __(ucfirst($color->name)).' '.$color->pivot->quantity }}
                                    @endforeach
                                @else
                                    No tiene color
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($product->sizes->count())
                                    @foreach($product->sizes as $size)
                                        <b>{{ ucfirst($size->name) }} </b>
                                        @foreach($size->colors as $color)
                                            {{ __(ucfirst($color->name)).' '.$color->pivot->quantity }}
                                        @endforeach
                                    @endforeach
                                @else
                                    No tiene tallas
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    No existen productos coincidentes.
                </div>
            @endif

            @if($products->hasPages())
                <div class="px-6 py-4">
                    {{ $products->links() }}
                </div>
            @endif
        </x-table-responsive>
    </div>
</div>
