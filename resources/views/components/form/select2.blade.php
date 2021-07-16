@props([
    'name'            => '',
    'selectTitle'     => 'Select Items',
    'class'           => 'select2' ,
    'col'             => '12',
    'options'         => [],
    'value'           => '',
    'required'        => false,
    'isDisabled'      => false,
])

<div {{ $attributes }}>
    <select class="choices form-select" x-data @change="$dispatch('input',$event.target.value)">
        <option value="0">
            {{ $selectTitle }}
        </option>
        @foreach($options as $option)
            <option
                {{ $isDisabled ? 'disabled' : '' }} {{ $option['id']  == $value ? 'selected' : '' }} value="{{ $option['id'] }}">{{ $option['name'] }}</option>
        @endforeach
    </select>
</div>

