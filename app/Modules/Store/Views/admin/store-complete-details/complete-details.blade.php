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
{{--                        <div id="showStoreFlashMessage"></div>--}}
                        <div class="panel panel-default">
                            @include("Store::admin.store-complete-details.layout.common.panel-heading")

                            <div class="panel-body" style="background-color: #ecf0f5;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div  class="nav-tabs-custom">
                                            @include('Store::admin.store-complete-details.layout.common.nav-tab')
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
    @include('Store::admin.store-complete-details.layout.partials.general-detail.css')
@endpush

@push('scripts')
    @include('Store::admin.store-complete-details.layout.common.scripts')
    @include('Store::admin.store-complete-details.layout.partials.miscellaneous-payment.scripts')
    @include('Store::admin.store-complete-details.layout.partials.balance.scripts')
    @include('Store::admin.store-complete-details.layout.partials.order.scripts')
    @include('Store::admin.store-complete-details.layout.partials.pre-order.scripts')
@endpush


