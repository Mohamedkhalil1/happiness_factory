@props([
    'title'       => '',
    'required'    => false
])

<label {{ $attributes }}>
    {{ $title }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
