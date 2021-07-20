<div>
    <div>
        @section('title',$pageTitle)
        <x-base.card title="{{ $pageTitle }}">
            <x-general.progress-bar/>
            <x-base.nav>
                <x-nav.element wire:click="active('client')" style="cursor: pointer" :active="$activeClient">
                    Client
                </x-nav.element>
                <x-nav.element wire:click="active('calculations')" style="cursor: pointer"
                               :active="$activeCalculations">
                    Calculations
                </x-nav.element>
                <x-nav.element wire:click="active('products')" style="cursor: pointer" :active="$activeProducts">
                    Products
                </x-nav.element>
                <x-nav.element wire:click="active('transactions')" style="cursor: pointer"
                               :active="$activeTransactions">Transactions
                </x-nav.element>
            </x-base.nav>
            <div class="mt-3">
                @if($activeClient)
                    @include('livewire.order.partials.client')
                @elseif($activeCalculations)
                    @include('livewire.order.partials.calculations')
                @elseif($activeProducts)
                    @include('livewire.order.partials.products')
                @elseif($activeTransactions)
                    @include('livewire.order.partials.transactions')
                @endif
            </div>

        </x-base.card>
    </div>
</div>
