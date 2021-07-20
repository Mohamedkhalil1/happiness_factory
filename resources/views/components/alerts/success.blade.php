@props(['icon' => 'bi bi-check-circle'])

<div class="alert alert-light-success color-success">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</div>
