<section class="content-header">
    <h1>
        {{isset($page_title) ? $page_title : ''}}
        <small>{{isset($sub_title) ? $sub_title : ''}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('warehouse.dashboard')}}"><i class="fa fa-{{isset($icon) ? $icon : ''}}"></i> Dashboard</a></li>
        <li class="active"><a href="{{isset($manage_url) ? $manage_url : route('warehouse.dashboard')}}"><i class="fa fa-{{isset($sub_icon) ? $sub_icon : ''}}"></i> {{isset($page_title) ? $page_title : ''}}</a></li>
    </ol>
</section>