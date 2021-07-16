<li class="sidebar-item {{ $setIsActive() ?  'active' : '' }} {{ $isSingle ? '' : 'has-sub' }} ">
    <a href="{{ $url }}" class='sidebar-link'>
        <i class="{{ $icon }}"></i>
        <span>{{ $title }}</span>
    </a>
    @if(!$isSingle)
        <x-general.partial.nesteditems
            :isActive="$setIsActive()"
            :items="$items"
        />
    @endif
</li>
