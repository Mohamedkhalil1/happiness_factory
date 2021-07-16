@props(['title' => '' , 'name' => '' ])

<div class="form-check form-switch">
    <input wire:model="{{ $name }}" class="form-check-input" type="checkbox" id="{{ $name }}">
    <label class="form-check-label" for="{{ $name }}">{{ $title }}</label>
</div>
