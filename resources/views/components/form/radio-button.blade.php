@props([
    'title',
    'name',
    'value'=>'',
    'class'  => ''
])

<div class="form-check">
    <input
        {{ $attributes }}
        class="form-check-input {{ $class }}"
        type="radio"
        value="{{ $value }}">

    <label class="form-check-label" for="{{ $value }}">
        {{ $title }}
    </label>
</div>
