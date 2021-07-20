@props(['active' => false])
@php
    $active = $active ? 'active' : '';
@endphp
<li class="nav-item" role="presentation">
    <a {{ $attributes->merge(['class'=> "nav-link $active"]) }}
       role="tab" aria-controls="home" aria-selected="true">
        {{ $slot }}
    </a>
</li>
