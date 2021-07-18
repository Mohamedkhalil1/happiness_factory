@props(['sortable' => false , 'direction'])

<th {{ $attributes->merge(['class' => 'text-muted table-light text-sm text-uppercase']) }}>
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
