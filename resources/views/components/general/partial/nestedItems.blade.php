@props(['items', 'isActive' => false])
<ul class="submenu {{ $isActive ? 'active' : '' }}">
    @foreach($items as $item)
        <li class="submenu-item {{ $item['active'] ? 'active' : '' }}">
            <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
        </li>
    @endforeach
</ul>
