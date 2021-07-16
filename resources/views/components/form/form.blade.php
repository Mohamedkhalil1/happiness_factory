@props(['action'  => '' ,'isHorizontal' => false])
@php
    $class = $isHorizontal  ? 'form-horizontal' : '';
@endphp
<form {{ $attributes }} wire:submit.prevent="{{ $action }}" class="form {{ $class }}">
    <div class="form-body">
        <div class="row">
            {{ $slot }}
        </div>
    </div>
    <div
        class="col-md-1 float-left align-center text-center tox-collection__item-container--valign-middle">
    </div>
</form>
