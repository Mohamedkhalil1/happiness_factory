@props([
    'title',
    'color'   => 'primary',
    'class'   => '',
    'id'      => null
])

<a {{ $attributes }} {{ $id ? "id=$id" : '' }} class="btn btn-{{$color}} {{$class}}">
    {{ $slot }}
</a>
