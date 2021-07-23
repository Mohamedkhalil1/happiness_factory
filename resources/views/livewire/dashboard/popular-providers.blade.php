<x-base.table class="border">
    <x-slot name="head">

        <x-table.heading>
            Name
        </x-table.heading>

        <x-table.heading>
            Type
        </x-table.heading>

        <x-table.heading>
            Total Amount
        </x-table.heading>

        <x-table.heading>
            Paid Amount
        </x-table.heading>

        <x-table.heading>
            Remain
        </x-table.heading>

        <x-table.heading>
            Phone
        </x-table.heading>

        <x-table.heading>
            Address
        </x-table.heading>

        <x-table.heading>
            Worked Date
        </x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse($models as $model)
            <x-table.row wire:key="{{ $model->id }}">
                <x-table.cell>{{ $model->name }}</x-table.cell>
                <x-table.cell>
                    <x-base.badge type="{{\App\Enums\ClientType::getColor($model->type)  }}">
                        {{ \App\Enums\ClientType::name($model->type) }}
                    </x-base.badge>
                </x-table.cell>
                <x-table.cell>{{ $model->total_amount }}</x-table.cell>
                <x-table.cell>{{ $model->paid_amount }}</x-table.cell>
                <x-table.cell>{{ $model->remain }}</x-table.cell>

                <x-table.cell>{{ $model->phone }}</x-table.cell>
                <x-table.cell>{{ $model->address }}</x-table.cell>
                <x-table.cell>{{ formatDate($model->worked_date) }}</x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="11">
                    <div class="text-center text-muted text-uppercase">
                        No providers found...
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-base.table>
