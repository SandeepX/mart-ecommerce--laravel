@canany(['View Brand List','View Category List','View Category Brand List',
'View Variant List','View Product List','View Location List'])
<li class=" {{request()->routeIs('admin.brands.*') ||
request()->routeIs('admin.categories.*') ||
request()->routeIs('admin.variants.*') ||
request()->routeIs('admin.brands.*') ||
request()->routeIs('admin.location-hierarchies.*')
? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>E-commerce</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>

    <ul class="treeview-menu">

        @can('View Brand List')
            @includeIf('Admin::layout.partials.sidebar.ecommerce.brand.brand')
        @endcan

        @can('View Category List')
            @includeIf('Admin::layout.partials.sidebar.ecommerce.category.category')
        @endcan

        @can('View Category Brand List')
            @includeIf('Admin::layout.partials.sidebar.ecommerce.category.category-brands')
        @endcan

        @can('View Variant List')
            @includeIf('Admin::layout.partials.sidebar.ecommerce.variants.variants')
        @endcan

        @can('View Product List')
            @includeIf('Admin::layout.partials.sidebar.ecommerce.product.main')
        @endcan


        @can('View Location List')
            <li class="{{request()->routeIs('admin.location-hierarchies.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.location-hierarchies.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.location-hierarchies.index') ? 'fa-spin' : ''}}"></i>
                    <span>Location</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan

    </ul>


</li>
@endcanany


