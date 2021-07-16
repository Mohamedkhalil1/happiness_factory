@props([
    'title',
    'type'    => 'button',
    'class'   => 'primary',
    'id'      => null
      ])

<button {{ $attributes }} type='{{$type}}' {{ $id ? "id=$id" : '' }} class="btn btn-{{$class}}">
    {{ $slot }}
</button>
