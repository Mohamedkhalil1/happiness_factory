@props(['name' => ''])
<select {{ $attributes->merge(['class' => "form-select"]) }}>
    {{ $slot }}
</select>
@error($name)<span class="text-danger">{{ $message }}</span>@enderror
