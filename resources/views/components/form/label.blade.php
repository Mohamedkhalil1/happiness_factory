@props([
'title'         => '',
'required'      => false,
'hint'          => null
])

<label {{ $attributes }}>
    {{ $title }}
    @if($hint)
        <p class="d-inline" class="text-muted text-sm">({{ $hint }})</p>
    @endif
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
