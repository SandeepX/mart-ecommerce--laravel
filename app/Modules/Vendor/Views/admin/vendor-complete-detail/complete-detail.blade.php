@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> " View {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="box box-primary">
                        @include("Admin::layout.partials.flash_message")
                        <div class="panel panel-default">
                            @include("Vendor::admin.vendor-complete-detail.layout.common.panel-heading")

                            <div class="panel-body" style="background-color: #ecf0f5;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div  class="nav-tabs-custom">
                                            @include('Vendor::admin.vendor-complete-detail.layout.common.nav-tab')
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="general-content">
                                                    {{--                                                    main content--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('css')
    @include('Vendor::admin.vendor-complete-detail.layout.partials.general-detail.css')
@endpush

@push('scripts')
    @include('Vendor::admin.vendor-complete-detail.layout.common.scripts')
@endpush


