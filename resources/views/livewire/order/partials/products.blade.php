<x-base.table class="border">
    <x-slot name="head">
        <x-table.heading>
            Image
        </x-table.heading>

        <x-table.heading>
            Name
        </x-table.heading>

        <x-table.heading >
            Color
        </x-table.heading>

        <x-table.heading >
            Size
        </x-table.heading>

        <x-table.heading >
            Price
        </x-table.heading>

        <x-table.heading >
            Quantity
        </x-table.heading>

        <x-table.heading >
            Total
        </x-table.heading>

    </x-slot>

    <x-slot name="body">
        @forelse($products as $product)
            <x-table.row wire:key="{{ $product->id }}">
                <x-table.cell>
                    @if(isset($product->image) && $product->image)
                        <a target="_blank" href="{{  getImageUrl($product->image) }}">
                            <x-base.avatar imageUrl="{{ getImageUrl($product->image) }}"/>
                        </a>

                    @endif
                </x-table.cell>
                <x-table.cell>{{ $product->product->name ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $product->color }}</x-table.cell>
                <x-table.cell>{{ $product->size }}</x-table.cell>
                <x-table.cell>{{ formatMoney($product->orderDetails->first()->price) }}</x-table.cell>
                <x-table.cell>{{ $product->orderDetails->first()->quantity }}</x-table.cell>
                <x-table.cell>{{formatMoney($product->orderDetails->first()->price * $product->orderDetails->first()->quantity) }}</x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="11">
                    <div class="text-center text-muted text-uppercase">
                        No inventories found...
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-base.table>
<div>
    {{ $products->links() }}
</div>
