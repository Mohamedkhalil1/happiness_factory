<div>
    <div>
        @section('title',$pageTitle)
        <x-base.card title="{{ $pageTitle }}">
            <x-general.progress-bar/>
            <x-base.nav>
                <x-nav.element wire:click="active('provider')" style="cursor: pointer" :active="$activeProvider">
                    Provider
                </x-nav.element>
                <x-nav.element wire:click="active('calculations')" style="cursor: pointer"
                               :active="$activeCalculations">
                    Calculations
                </x-nav.element>
                <x-nav.element wire:click="active('transfers')" style="cursor: pointer"
                               :active="$activeTransfers">Transfers
                </x-nav.element>
            </x-base.nav>
            <div class="mt-3">
                @if($activeProvider)
                    @include('livewire.purchase.partials.provider')
                @elseif($activeCalculations)
                    @include('livewire.purchase.partials.calculations')
                @elseif($activeTransfers)
                   @include('livewire.purchase.partials.transfers')
                @endif
            </div>

        </x-base.card>
    </div>
</div>
