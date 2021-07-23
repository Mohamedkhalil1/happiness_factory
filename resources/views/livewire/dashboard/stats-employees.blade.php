<x-base.table class="border">
    <x-slot name="head">

        <x-table.heading>
            Avatar
        </x-table.heading>

        <x-table.heading>
            Name
        </x-table.heading>

        <x-table.heading>
            Type
        </x-table.heading>

        <x-table.heading>
            Phone
        </x-table.heading>

        <x-table.heading>
            Address
        </x-table.heading>

        <x-table.heading>
            Status
        </x-table.heading>

        <x-table.heading>
            Category
        </x-table.heading>

        <x-table.heading>
            Salary
        </x-table.heading>

        <x-table.heading>
            Worked Date
        </x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse($models as $model)
            <x-table.row wire:key="{{ $model->id }}">
                <x-table.cell>
                    @if(isset($model->avatar) && $model->avatar)
                        <x-base.avatar imageUrl="{{ $model->getAvatar() }}"/>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    {{ $model->name }}
                    <br>
                    <span class="text-muted text-sm">({{ $model->nickname }})</span>
                </x-table.cell>
                <x-table.cell>
                    <x-base.badge type="{{\App\Enums\EmployeeType::getColor($model->type)  }}">
                        {{ \App\Enums\EmployeeType::name($model->type) }}
                    </x-base.badge>
                </x-table.cell>
                <x-table.cell>{{ $model->phone }}</x-table.cell>
                <x-table.cell>{{ $model->address }}</x-table.cell>
                <x-table.cell>
                    <x-base.badge type="{{\App\Enums\SocialStatus::getColor($model->social_status)  }}">
                        {{ \App\Enums\SocialStatus::name($model->social_status) }}
                    </x-base.badge>
                </x-table.cell>
                <x-table.cell>{{ $model->category->name ?? '' }}</x-table.cell>
                <x-table.cell>{{ formatMoney($model->salary) }}</x-table.cell>
                <x-table.cell>{{ formatDate($model->worked_date) }}</x-table.cell>

            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="11">
                    <div class="text-center text-muted text-uppercase">
                        No employees found...
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-base.table>
