<x-base.grid>
    <x-base.grid-col>
        <x-alerts.primary icon="bi bi-lightning-fill">
            Name: {{ $client->name }}
        </x-alerts.primary>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-star">
            Type: {{ \App\Enums\ClientType::name($client->type)  }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.danger icon="bi bi-star">
            Address: {{ $client->address }}
        </x-alerts.danger>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.secondary icon="bi bi-star">
            Phone: {{ $client->phone }}
        </x-alerts.secondary>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-star">
            Worked Date: {{ formatDate($client->worked_date) }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.danger icon="bi bi-star">
            details: {{ $client->details }}
        </x-alerts.danger>
    </x-base.grid-col>

</x-base.grid>
