{{--PROGRESS BAR--}}
<x-base.grid>
    <x-base.grid-col col="12">
        <div wire:loading.flex class="progress progress-primary loading-bar">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                 style="width: 100%"
                 aria-valuenow="100"
                 aria-valuemin="0"
                 aria-valuemax="100">
            </div>
        </div>
    </x-base.grid-col>
</x-base.grid>
