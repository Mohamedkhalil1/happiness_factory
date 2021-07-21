@props(['name' => '' ,'disabled'=>false])

<select {{ $attributes->merge(['class' => "form-select"]) }} {{ $disabled  ? 'disabled' : '' }}>
    {{ $slot }}
</select>
@error($name)<span class="text-danger">{{ $message }}</span>@enderror
