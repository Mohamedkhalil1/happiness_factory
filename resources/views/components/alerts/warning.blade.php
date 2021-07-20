@props(['icon' => 'bi bi-exclamation-triangle'])

<div class="alert alert-light-warning color-warning">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</div>
