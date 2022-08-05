<section class="content-header">
    <h1>
        {{isset($page_title) ? $page_title : ''}}
        <small>{{isset($sub_title) ? $sub_title : ''}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('admin.dashboard')}}"><i class="fa fa-{{isset($icon) ? $icon : ''}}"></i> Dashboard</a></li>
        <li class="active"><a href="{{isset($void) ? $void : url($manage_url)}}"><i class="fa fa-{{isset($sub_icon) ? $sub_icon : ''}}"></i> {{isset($page_title) ? $page_title : ''}}</a></li>
        @if (isset($extraBreadCrumbs) && count($extraBreadCrumbs) > 0)
            @foreach($extraBreadCrumbs as $breadCrumbName => $extraBreadCrumbUrl)
             <li class="active">
                 <a href="{{isset($extraBreadCrumbUrl) ? url($extraBreadCrumbUrl) : "javascript:void(0)"}}">
                     {{isset($breadCrumbName) ? $breadCrumbName : ''}}
                 </a>
             </li>
        @endforeach
        @endif
    </ol>
</section>
