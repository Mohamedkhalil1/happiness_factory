@props(['image', 'size' => null, 'img' => false, 'alt' => '', 'crop' => false, 'imageClass' => ''])

@php
    //$imageModel = $image ?? new \Designfy\Template\Models\File();
    //$extension = $imageModel->extension;
@endphp
<a href="{{$image->getUrl()}}" target="_blank">
    <img src="{{$image->getUrl($size)}}" {{ $attributes }}>
</a>
