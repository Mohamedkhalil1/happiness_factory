@props([
'name'            => '',
])

<div {{ $attributes }}>
    <select class="choices form-select @error($name) is-invalid @enderror" x-data
            @change="$dispatch('input',$event.target.value)">
        {{ $slot }}
    </select>
</div>
@error($name)<span class="text-danger">{{ $message }}</span>@enderror
