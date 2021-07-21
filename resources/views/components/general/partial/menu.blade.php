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
                        'title'  => 'Seasons',
                        'url'    => route('seasons.index'),
                        'active' => isActive('seasons.index')
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
            title="Orders"
            icon="bi bi-cart-plus-fill"
            :isSingle="false"
            :items="
                [
                    [
                        'title'  => 'All Orders',
                        'url'    => route('orders.index'),
                        'active' => isActive('orders.index') ||  isActive('orders.show')
                    ],
                    [
                        'title'  => 'Transactions',
                        'url'    => route('transactions.index'),
                        'active' => isActive('transactions.index')
                    ],
                    [
                        'title'  => 'Make Order',
                        'url'    => route('orders.create'),
                        'active' => isActive('orders.create')
                    ],
                ]
            "></x-menu-item>

        <x-menu-item
            title="Supplies"
            icon="bi bi-server"
            :isSingle="false"
            :items="
                [
                    [
                        'title'  => 'Providers',
                        'url'    => route('providers.index'),
                        'active' => isActive('providers.index')
                    ],
                    [
                        'title'  => 'Materials',
                        'url'    => route('materials.index'),
                        'active' => isActive('materials.index')
                    ],
                    [
                        'title'  => 'Ores',
                        'url'    => route('materials.ores.index'),
                        'active' => isActive('materials.ores.index')
                    ],
                      [
                        'title'  => 'Buy Ores',
                        'url'    => route('materials.ores.buy'),
                        'active' => isActive('materials.ores.buy') ||  isActive('materials.ores.purchase.show')
                    ],

                     [
                        'title'  => 'Transfers',
                        'url'    => route('materials.transfers.index'),
                        'active' => isActive('materials.transfers.index')
                    ],


                ]
            "></x-menu-item>

        <x-menu-item
            title="Clients"
            icon="bi bi-people-fill"
            :isSingle="false"
            :items="
                [
                    [
                        'title'  => 'Clients',
                        'url'    => route('clients.index'),
                        'active' => isActive('clients.index')
                    ],
                ]
            "></x-menu-item>

    </ul>
</div>
<button class="sidebar-toggler btn x"><i data-feather="x"></i></button>



