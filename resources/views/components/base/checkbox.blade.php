@props([
    'color'         => 'primary',
 ])

<div class="form-check">
    <div class="custom-control custom-checkbox">
        <input {{$attributes->merge(['class' => "form-check-input form-check-$color"]) }}
               type="checkbox">
    </div>
</div>

