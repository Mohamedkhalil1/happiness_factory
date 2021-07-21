<x-base.grid>
    <x-base.grid-col>
        <x-alerts.primary icon="bi bi-lightning-fill">
            Date: {{ formatDate($model->date) }}
        </x-alerts.primary>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-hash">
            Status: {{ \App\Enums\OrderStatus::name($model->status)  }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.danger icon="bi bi-cash">
            Total Amount: {{ formatMoney($model->amount_before_discount) }}
        </x-alerts.danger>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.secondary icon="bi bi-star">
            Discount: {{ $model->discount }} %
        </x-alerts.secondary>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-cash">
            Actual Amount: {{ formatMoney($model->amount_after_discount) }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.success icon="bi bi-cash">
            Actual Amount: {{ formatMoney($model->paid_amount) }}
        </x-alerts.success>
    </x-base.grid-col>

    <x-base.grid-col>
        <x-alerts.danger icon="bi bi-cash">
            Remain Amount: {{ formatMoney($model->remain) }}
        </x-alerts.danger>
    </x-base.grid-col>

</x-base.grid>
