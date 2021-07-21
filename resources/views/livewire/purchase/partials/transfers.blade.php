<x-base.table class="border">
    <x-slot name="head">
        <x-table.heading>
            Amount
        </x-table.heading>

        <x-table.heading>
            Date
        </x-table.heading>

        <x-table.heading>
            Note
        </x-table.heading>

    </x-slot>

    <x-slot name="body">

        @forelse($transfers as $transfer)
            <x-table.row wire:key="{{ $transfer->id }}">

                <x-table.cell>{{ formatMoney($transfer->amount) }}</x-table.cell>
                <x-table.cell>{{ formatDate($transfer->date) }}</x-table.cell>
                <x-table.cell>{{ $transfer->note }}</x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="11">
                    <div class="text-center text-muted text-uppercase">
                        No transfers found...
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-base.table>
<div>
    {{ $transfers->links() }}
</div>
