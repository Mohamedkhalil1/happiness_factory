@props(['icon' => 'bi bi-star'])

<div class="alert alert-light-secondary color-secondary">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</div>
