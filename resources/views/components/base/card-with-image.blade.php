{{--<div class="card">--}}
{{--    <div class="card-content">--}}
{{--        <img class="card-img-top img-fluid" src="{{ asset($imageUrl) }}"--}}
{{--             alt="Card image cap" style="height: 20rem"/>--}}
{{--        <div class="card-body">--}}
{{--            <h4 class="card-title">{{ $title }}</h4>--}}
{{--            <p class="card-text">--}}
{{--                {{ $description }}--}}
{{--            </p>--}}
{{--            <p class="card-text">--}}
{{--                {{ $footerText }}--}}
{{--            </p>--}}
{{--            <button wire:click="{{ $action }}" class="btn btn-outline-primary btn-sm">--}}
{{--                <i class="iconly-boldCall"></i> Add To--}}
{{--            </button>--}}
{{--            <button wire:click="{{$removeFunction}}('{{$title}}')" class="btn btn-outline-danger btn-sm">--}}
{{--                <i class="iconly-boldCall"></i> Remove--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div {{ $attributes->merge(['class' => 'card']) }}>
    {{ $slot }}
</div>
