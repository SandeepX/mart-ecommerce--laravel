@canany(['View Brand List','View Category List','View Category Brand List',
'View Variant List','View Product List','View Location List','View Product Collection List','View Slider List'])
<li class=" {{request()->routeIs('admin.brands.*') ||
request()->routeIs('admin.categories.*') ||
request()->routeIs('admin.variants.*') ||
request()->routeIs('admin.brands.*') ||
request()->routeIs('admin.location-hierarchies.*') ||
request()->routeIs('admin.location-blacklisted.*') ||
request()->routeIs('admin.product-collections.*') ||
request()->routeIs('admin.sliders.*')
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

        @can('View Blacklisted List')
            <li class="{{request()->routeIs('admin.location-blacklisted.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.location-blacklisted.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.location-blacklisted.index') ? 'fa-spin' : ''}}"></i>
                    <span>Blacklist Location</span>
                    <span class="pull-right-container">
        </span>
                </a>
            </li>
        @endcan
            @can('View Product Collection List')
                <li class="{{request()->routeIs('admin.product-collections.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.product-collections.index')}}">
                        <i class="fa fa-circle-o {{request()->routeIs('admin.product-collections.index') ? 'fa-spin' : ''}}"></i>
                        <span>Product Collections</span>
                        <span class="pull-right-container">
                </span>
                    </a>
                </li>
            @endcan
            @can('View Slider List')
                <li class="{{request()->routeIs('admin.sliders.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.sliders.index')}}">
                        <i class="fa fa-circle-o {{request()->routeIs('admin.sliders.index') ? 'fa-spin' : ''}}"></i> <span>Sliders</span>
                        <span class="pull-right-container">
                </span>
                    </a>
                </li>
            @endcan

    </ul>


</li>
@endcanany


