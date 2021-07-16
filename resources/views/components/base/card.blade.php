@props(['title' => null , 'footer' => null ])
<div {{ $attributes->merge(['class' => 'card' ]) }}>
    @if ($title)
        <div class="card-header ">
            <div class="card-title divider ">
                <h4 class="card-title divider-text">{{ $title }}</h4>
            </div>
        </div>
    @endif
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                {{ $slot }}
            </div>
        </div>
    </div>

    @isset($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endisset
</div>
