@props(['icon' => 'bi bi-star'])

<div class="alert alert-light-info color-info">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</div>
