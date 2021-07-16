@props(['sortable' => false , 'direction'])

<th {{ $attributes->merge(['class' => 'table-light text-muted text-sm text-uppercase']) }}>
    {{ $slot }}
    @if($sortable)
        @if( $direction == 'asc')
            <x-icons.down/>
        @else
            <x-icons.up/>
        @endif
    @endif
</th>
@once
    @push('head')
        <style>
            th {
                padding: 1rem !important;
            }
        </style>
    @endpush
@endonce
