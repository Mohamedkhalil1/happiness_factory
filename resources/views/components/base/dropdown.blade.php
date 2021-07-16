@props(['label' => '','color' => null])
@php
    $color =[
        'light' => 'btn-light',
        'dark' => 'btn-dark',
        'primary' => 'btn-primary',
        'info' => 'btn-info',
        'warning' => 'btn-warning',
        'secondary' => 'btn-secondary',
        ][$color ?? 'dark'];
@endphp

<div {{ $attributes->merge(['class' => "btn-group dropdown me-1 mb-1"]) }}>
    <button type="button"
            class = "btn {{$color}} dropdown-toggle dropdown-toggle-split"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            data-reference="parent">
        <span>{{ $label }}</span>
    </button>
    <div class="dropdown-menu">
        {{ $slot }}
    </div>
</div>
