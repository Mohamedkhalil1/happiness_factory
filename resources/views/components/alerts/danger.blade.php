@props(['icon' => 'bi bi-exclamation-circle'])

<div class="alert alert-light-danger color-danger">
    <i class="{{$icon}}"></i>
    {{$slot}}
</div>
