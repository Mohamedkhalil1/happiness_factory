@props(['id','name','class'=>''])
<div
    x-data
    x-init="new Pikaday({ field: $refs.{{$id}} , format: 'YYYY/MM/DD', })">
    <input
        x-ref="{{ $id }}"
        {{ $attributes }}
        wire:model.lazy="{{ $name }}"
        class="form-control {{$class}} @error($name) is-invalid @enderror"
    >
</div>
@error($name) <span class="text-danger"> {{ $message }}  </span>@enderror

@once
    @push('head')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
        <style>
            .pika-lendar {
                float: left;
                width: 370px !important;
                margin: 8px;
            }
        </style>
    @endpush

    @push('script')
        <script src="https://unpkg.com/moment"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    @endpush
@endonce
