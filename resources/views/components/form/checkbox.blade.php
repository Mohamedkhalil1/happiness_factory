@props([
    'title',
    'name'          => '',
    'required'      => false,
    'color'         => 'primary',
    'inputClass'    => '',
    'labelClass'    => ''
 ])

<div class="form-check">
    <div class="custom-control custom-checkbox">
        <input {{$attributes }} type="checkbox"
               class="form-check-input form-check-{{$color}} {{$inputClass}}"
               wire:model="{{ $name }}" id="{{ $name }}">
        <label class="form-check-label {{ $labelClass }}" for="{{ $name }}">{{ $title }}</label>
    </div>
</div>

