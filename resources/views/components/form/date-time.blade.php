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


