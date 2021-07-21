<x-base.grid>
    <x-base.grid-col>
        <x-alerts.primary icon="bi bi-lightning-fill">
            Name: {{ $provider->name }}
        </x-alerts.primary>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-star">
            Type: {{ \App\Enums\ClientType::name($provider->type)  }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.danger icon="bi bi-star">
            Address: {{ $provider->address }}
        </x-alerts.danger>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.secondary icon="bi bi-star">
            Phone: {{ $provider->phone }}
        </x-alerts.secondary>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-star">
            Worked Date: {{ formatDate($provider->worked_date) }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.danger icon="bi bi-star">
            details: {{ $provider->details }}
        </x-alerts.danger>
    </x-base.grid-col>

</x-base.grid>
