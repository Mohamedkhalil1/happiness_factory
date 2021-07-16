@props([
    'selectTitle'     => 'Select Items',
    'options'         => [],
    'required'        => false,
    'isDisabled'      => false,
    'name'            => ''
])

<select {{ $attributes->merge(['class' => 'form-select']) }}>
    <option value="" selected readonly>
        {{ $selectTitle }}
    </option>
    @foreach($options as $option)
        <option
            {{ $isDisabled ? 'disabled' : '' }} value="{{ $option['id'] }}">{{ $option['name'] }}</option>
    @endforeach
</select>

@error($name)<span class="text-danger">{{ $message }}</span>@enderror
