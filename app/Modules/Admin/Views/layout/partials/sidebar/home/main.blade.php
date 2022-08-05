@canany(['View Product Collection List','View Slider List'])
<li class="
{{
   request()->routeIs('admin.product-collections.*')
? 'active' : ''
}}
        treeview
">
    <a href="#">
        <i class="fa fa-home"></i>
        <span>Home Page Section</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
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
