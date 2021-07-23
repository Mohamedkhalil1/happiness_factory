<x-base.grid>
    <x-design.statistics icon="iconly-boldBag" color="purple">
        <x-slot name="name">
            Products No.
        </x-slot>
        <x-slot name="value">
            {{ \App\Models\Inventory::query()->sum('quantity')}} Pieces
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldBuy" color="blue">
        <x-slot name="name">
            Orders No.
        </x-slot>
        <x-slot name="value">
            {{ \App\Models\Order::query()->count()}} Orders
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldProfile" color="green">
        <x-slot name="name">
            Clients No.
        </x-slot>
        <x-slot name="value">
            {{ \App\Models\Client::query()->count()}} Persons
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldProfile" color="red">
        <x-slot name="name">
            Providers No.
        </x-slot>
        <x-slot name="value">
            {{ \App\Models\Provider::query()->count()}} Persons
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldBag" color="purple">
        <x-slot name="name">
            Products
        </x-slot>
        <x-slot name="value">
            {{ formatMoney(\App\Models\Inventory::query()->sum(\DB::raw('quantity * price')))  }}
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldDocument" color="blue">
        <x-slot name="name">
            Orders
        </x-slot>
        <x-slot name="value">
            {{ formatMoney(\App\Models\Order::query()->sum('amount_after_discount')) }}
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldDocument" color="green">
        <x-slot name="name">
            Paid Orders
        </x-slot>
        <x-slot name="value">
            {{  formatMoney(\App\Models\Order::query()->sum(\DB::raw('amount_after_discount - remain'))) }}
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldDocument" color="red">
        <x-slot name="name">
            Remain Orders
        </x-slot>
        <x-slot name="value">
            {{  formatMoney(\App\Models\Order::query()->sum(\DB::raw('remain'))) }}
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldUser" color="purple">
        <x-slot name="name">
            Employees No.
        </x-slot>
        <x-slot name="value">
            {{ \App\Models\Employee::query()->count() }} Employees
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldCategory" color="blue">
        <x-slot name="name">
            Materials No.
        </x-slot>
        <x-slot name="value">
            {{  \App\Models\Material::query()->count() }} Materials
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldWallet" color="green">
        <x-slot name="name">
            Ore Operations No.
        </x-slot>
        <x-slot name="value">
            {{  \App\Models\Transfer::query()->count() }} Ores
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldFilter" color="red">
        <x-slot name="name">
            Accessories No.
        </x-slot>
        <x-slot name="value">
            {{  \App\Models\Accessory::query()->count() }} Accessories
        </x-slot>
    </x-design.statistics>


    <x-design.statistics icon="iconly-boldUser" color="purple">
        <x-slot name="name">
            Salaries Amount
        </x-slot>
        <x-slot name="value">
            {{ formatMoney(\App\Models\Salary::query()->sum('total_amount')) }}
        </x-slot>
    </x-design.statistics>

    <x-design.statistics icon="iconly-boldCategory" color="blue">
        <x-slot name="name">
            Materials
        </x-slot>
        <x-slot name="value">
            {{ formatMoney(\App\Models\Purchase::query()->sum('paid_amount')) }}
        </x-slot>
    </x-design.statistics>


    <x-design.statistics icon="iconly-boldFilter" color="red">
        <x-slot name="name">
            Accessories
        </x-slot>
        <x-slot name="value">
            {{  formatMoney(\App\Models\Accessory::query()->sum('amount')) }}
        </x-slot>
    </x-design.statistics>

</x-base.grid>
