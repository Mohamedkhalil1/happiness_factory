@props(['id','type'=>'','color'=> '' ,'size' => '' , 'formAction' => null ,'errors' => []])

@php
    $types = [
        'center' => 'modal-dialog-centered',
    ];

    $colors=[
        'primary' => 'bg-primary',
        'success' => 'bg-success',
        'dark'    => 'bg-dark',
        'danger'  => 'bg-danger',
    ];

    $sizes =[
        'sm'    => 'modal-sm',
        'lg'    => 'modal-lg',
        'xl'    => 'modal-xl',
        'full'  => 'modal-full'
    ];
@endphp
<div wire:ignore.self class="modal fade text-left" id="{{ $id }}" tabindex="-1"
     role="dialog" aria-labelledby="My-{{$id}}"
     aria-hidden="true">
    {{ $slot }}
    @if($formAction)
        <x-form.form :action="$formAction">
            <div class="col-md-12">
                @endif
                <div class="modal-dialog modal-dialog-scrollable {{ $sizes[$size] ?? '' }} {{ $types[$type] ?? '' }}"
                     role="document">
                    <div class="modal-content">
                        <div class="modal-header {{ $colors[$color] ?? '' }}">
                            <h5 class="modal-title {{ $color ? 'text-white' : '' }}" id="My-{{$id}}">
                                {{ $title }}
                            </h5>
                            <button type="button" class="close"
                                    data-bs-dismiss="modal" aria-label="Close">
                                <x-icons.close/>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ $content }}
                        </div>
                        <div class="modal-footer">
                            {{ $footer }}
                        </div>
                    </div>
                </div>
                @if($formAction)
            </div>
        </x-form.form>
    @endif
</div>
