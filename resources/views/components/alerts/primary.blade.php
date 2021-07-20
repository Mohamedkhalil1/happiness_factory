@props(['icon' => 'bi bi-star'])

<div class="alert alert-light-primary color-primary">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</div>
