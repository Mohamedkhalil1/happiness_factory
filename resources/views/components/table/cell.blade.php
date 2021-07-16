@props(['value'])
<td {{$attributes->merge(['class' => 'text-muted'])}} >
    {{ $slot }}
</td>
