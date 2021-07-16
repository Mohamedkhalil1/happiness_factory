<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>
        <x-menu-item
            title="Dashboard"
            icon="bi bi-grid-fill"
            url="{{ route('dashboard') }}"
            isActive="{{ isActive('dashboard') }}"
            :isSingle="true"></x-menu-item>

        <x-menu-item
            title="Employees"
            icon="bi bi-person-fill"
            :isSingle="false"
            :items="
                [
                    [
                        'title'  => 'Categories',
                        'url'    => route('employees.categories.index'),
                        'active' => isActive('employees.categories.index')
                     ],
                ]
            "></x-menu-item>

    </ul>
</div>
<button class="sidebar-toggler btn x"><i data-feather="x"></i></button>



