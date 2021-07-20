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

        @forelse($transactions as $transaction)
            <x-table.row wire:key="{{ $transaction->id }}">

                <x-table.cell>{{ formatMoney($transaction->amount) }}</x-table.cell>
                <x-table.cell>{{ formatDate($transaction->date) }}</x-table.cell>
                <x-table.cell>{{ $transaction->note }}</x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="11">
                    <div class="text-center text-muted text-uppercase">
                        No transactions found...
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-base.table>
<div>
    {{ $transactions->links() }}
</div>
