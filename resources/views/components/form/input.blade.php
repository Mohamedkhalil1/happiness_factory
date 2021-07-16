@props([
    'title'         =>'',
    'name'          => '',
    'value'         => '',
    'type'          =>'text',
    'placeholder'   => '',
    'required'      => false,
    'isLivewire'    => true,
    'lazy'          => false,
    'class'         => '',
    'rightIcon'     => null,
    'inputGroupText'=> null
])

@if($inputGroupText)
    <div class="input-group">
@endif
        <input
            name="{{$name}}"
            {{ $attributes }}
            class="form-control {{$class}} @error($name) is-invalid @enderror"
            type='{{$type}}'
            {{ $required ? 'required' : '' }}
            @if($isLivewire)
            {{ $lazy ? "wire:model.lazy=$name" :  "wire:model=$name" }}
            @endif
            placeholder="{{$placeholder}}"
        >
@if($inputGroupText)
        <span class="input-group-text">{{ $inputGroupText }}</span>
    </div>
@endif
@error($name)<span class="text-danger">@error($name){{ $message }}@enderror</span>
@else
    @if($rightIcon)
        <div class="form-control-icon">
            <i class="{{ $rightIcon }}"></i>
        </div>
    @endif
@enderror

