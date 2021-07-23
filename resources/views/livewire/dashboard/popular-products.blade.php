<x-base.table class="border">
    <x-slot name="head">
        <x-table.heading>
            Image
        </x-table.heading>

        <x-table.heading>
            Name
        </x-table.heading>

        <x-table.heading>
            Price <p class="text-muted">(for one piece)</p>
        </x-table.heading>

        <x-table.heading>
            Quantity
        </x-table.heading>

        <x-table.heading>
            Amount
        </x-table.heading>

        <x-table.heading>
            Color
        </x-table.heading>

        <x-table.heading>
            Size
        </x-table.heading>

    </x-slot>

    <x-slot name="body">
        @forelse($orderDetails as $order)
            <x-table.row wire:key="{{ $order['inventory_id'] }}">
                <x-table.cell>
                    @if(isset($order['inventory']['image']) && $order['inventory']['image'])
                        <a target="_blank" href="{{  getImageUrl($order['inventory']['image']) }}">
                            <x-base.avatar imageUrl="{{ getImageUrl($order['inventory']['image']) }}"/>
                        </a>
                    @endif
                </x-table.cell>
                <x-table.cell>{{ $order['inventory']['product']['name'] ?? '-' }}</x-table.cell>
                <x-table.cell>{{ formatMoney($order['inventory']['price'] ?? '') }}</x-table.cell>
                <x-table.cell>{{ $order['inventory_quantities'] ?? '' }}</x-table.cell>
                <x-table.cell>{{ formatMoney(($order['inventory']['price'] ?? 0) *($order['inventory_quantities'] ??0)) }}</x-table.cell>
                <x-table.cell>{{ $order['inventory']['color'] ?? '' }}</x-table.cell>
                <x-table.cell>{{ $order['inventory']['size'] ?? '' }}</x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="11">
                    <div class="text-center text-muted text-uppercase">
                        No products found...
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-base.table>
