@props(['title', 'name', 'col'=>'12','file'=>null, 'required' => false])
<div class="col-md-{{$col}}">
    <div class="form-group">
        @isset($title)
            <label>{{ $title }}</label>
        @endisset
        @if($required)
            <span class="text-danger">*</span>
        @endif
        <div></div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="{{$name}}"
                   name='{{$name}}' {{ $required ? 'required' : '' }}>
            <label class="custom-file-label" for="customFile">
                {{$file->title??__('choose file')}}
            </label>
            @if($file && $file->title)
                <a href="{{$file->getUrl()}}"
                   target="_blank"
                   class="btn btn-sm btn-dark mt-1">
                    {{__('Preview')}}
                </a>
            @endif
        </div>
    </div>
</div>
@once
    @push('style')
        <style>
            .custom-file-input:lang(en) ~ .custom-file-label::after {
                content: 'Browse';
            }

            .custom-file-input:lang(ar) ~ .custom-file-label::after {
                content: "تصفح";
            }
        </style>
    @endpush
@endonce
