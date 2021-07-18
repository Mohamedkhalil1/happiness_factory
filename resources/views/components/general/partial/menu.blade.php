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
            title="Client"
            icon="bi bi-person-badge-fill"
            :isSingle="false"
            :items="
                [
                    [
                        'title'  => 'Client',
                        'url'    => route('clients.index'),
                        'active' => isActive('clients.index')
                    ],
                ]
            "></x-menu-item>

        <x-menu-item
            title="Products"
            icon="bi bi-collection-fill"
            :isSingle="false"
            :items="
                [
                    [
                        'title'  => 'Categories',
                        'url'    => route('products.categories.index'),
                        'active' => isActive('products.categories.index')
                    ],
                    [
                        'title'  => 'Products',
                        'url'    => route('products.index'),
                        'active' => isActive('products.index') || isActive('products.create')
                    ],
                    [
                        'title'  => 'Inventories',
                        'url'    => route('inventories.index'),
                        'active' => isActive('inventories.index')
                    ],
                ]
            "></x-menu-item>

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
                    [
                        'title'  => 'Employees',
                        'url'    => route('employees.index'),
                        'active' => isActive('employees.index')
                    ],
                     [
                        'title'  => 'Salaries',
                        'url'    => route('employees.salaries.index'),
                        'active' => isActive('employees.salaries.index')
                    ],
                    [
                        'title'  => 'Attendances',
                        'url'    => route('employees.attendances.index'),
                        'active' => isActive('employees.attendances.index')
                    ],
                ]
            "></x-menu-item>

        <x-menu-item
            title="Seasons"
            icon="bi bi-stack"
            url="{{ route('seasons.index') }}"
            isActive="{{ isActive('seasons.index') }}"
            :isSingle="true"></x-menu-item>

    </ul>
</div>
<button class="sidebar-toggler btn x"><i data-feather="x"></i></button>



