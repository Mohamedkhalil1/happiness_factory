@props(['size'=>'xl' , 'imageUrl' =>"asset('assets/images/faces/3.jpg')"])

<div class="avatar avatar-{{$size}} me-3">
    <img src="{{ $imageUrl }}" alt="avatar">
</div>
