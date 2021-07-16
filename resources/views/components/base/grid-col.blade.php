@props(['col' => 6])
<div {{ $attributes->merge(['class' =>  "col-md-$col"]) }}>
    {{ $slot }}
</div>
