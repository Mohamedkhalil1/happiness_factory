@section('title','Dashboard')
<div>
    <x-base.card>
        <x-general.progress-bar/>
        <x-base.nav>
            <x-nav.element wire:click="active('stats')" style="cursor: pointer" :active="$activeStats">
                Statistics
            </x-nav.element>

            <x-nav.element wire:click="active('popular-products')" style="cursor: pointer"
                           :active="$activePopularProducts">
                Popular Products
            </x-nav.element>

            <x-nav.element wire:click="active('popular-clients')" style="cursor: pointer"
                           :active="$activePopularClient">
                Popular Clients
            </x-nav.element>

            <x-nav.element wire:click="active('popular-providers')" style="cursor: pointer"
                           :active="$activePopularProvider">
                Popular Provider
            </x-nav.element>

            <x-nav.element wire:click="active('popular-employees')" style="cursor: pointer"
                           :active="$activePopularEmployee">
                Popular Employees
            </x-nav.element>

            <x-nav.element wire:click="active('unpopular-employees')" style="cursor: pointer"
                           :active="$activeUnpopularEmployee">
                Unpopular Employees
            </x-nav.element>

        </x-base.nav>
    </x-base.card>
    <div>
        @if($activeStats)
            @include('livewire.dashboard.stats')
        @elseif($activePopularProducts)
            @include('livewire.dashboard.popular-products')
        @elseif($activePopularClient)
            @include('livewire.dashboard.popular-clients')
        @elseif($activePopularEmployee)
            @include('livewire.dashboard.stats-employees',[
                'models' => $popular_employees
            ])
        @elseif($activeUnpopularEmployee)
            @include('livewire.dashboard.stats-employees',[
             'models' => $unpopular_employees
         ])

        @elseif($activePopularProvider)
            @include('livewire.dashboard.popular-providers',[
             'models' => $popular_providers
         ])
        @endif

    </div>


</div>
