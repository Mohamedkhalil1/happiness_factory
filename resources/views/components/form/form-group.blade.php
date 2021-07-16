@props([
    'col'       => null,
    'hasIcon'   => false,
    'class'     => ''
 ])
@php
    $class .= $hasIcon ? 'position-relative has-icon-right' : '';
    $class .= $col ? " col-md-$col" : '';
@endphp

<div class="form-group {{ $class }}">
    {{ $slot }}
</div>



