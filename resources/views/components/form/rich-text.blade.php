@props(['name','height'=>150 ,'initalValues'=>''])
<div
    {{ $attributes }}
    wire:ignore
    x-data
    @trix-blur="$dispatch('change',$event.target.value)">
    <input id="{{ $name }}"
           value="{{ $initalValues }}"
           type="hidden">
    {{--    Editor <strong>content</strong> goes here--}}
    <trix-editor input="{{ $name }}" class="form-control" style="height:{{ $height }}px"></trix-editor>

</div>
@once
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/trix@1.3.1/dist/trix.css">
    @endpush
    @push('script')
        <script src=" https://unpkg.com/trix@1.3.1/dist/trix.js"></script>
    @endpush
@endonce
