@extends('SupportAdmin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("SupportAdmin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> " View {$title}",
        'icon'=>'home',
        'sub_icon'=>''
        ])
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="box box-primary">
                        @include("SupportAdmin::layout.partials.flash_message")

                        <div class="alert alert-danger showFlashMessage" style="display:none">

                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="text-left">
                                    <div class="row" style="font-size: 13px; font-family: Arial, Helvetica, sans-serif;">
                                            <div class="col-sm-2">
                                                <i class="fa fa-user" ></i>
                                                {{$storeDetail['store_name'] }}
                                            </div>

                                            <div class="col-sm-3">
                                                <i class="fa fa-cog"></i> {{$storeDetail['store_code']}}
                                            </div>

                                        <div class="pull-right">
                                            <button style="margin-right:10px;" href="{{route('support-admin.store.index')}}" class="btn btn-info btn-sm back"><i class="fa fa-arrow-left"></i> Back</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body" style="background-color: #ecf0f5;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- ./col -->
{{--                                        <div class="col-lg-4 col-6">--}}
{{--                                            <!-- small box -->--}}
{{--                                            <div class="small-box bg-success" style="background-color: #0d6aad">--}}
{{--                                                <div class="inner">--}}
{{--                                                    <h3>{{($storeDetail['total_preorders']) ? $storeDetail['total_preorders'] : 0}}</h3>--}}
{{--                                                    <p>Store Preorder</p>--}}
{{--                                                </div>--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="ion ion-stats-bars"></i>--}}
{{--                                                </div>--}}
{{--                                                <a href="{{route('support-admin.store-preorder',$storeDetail['store_code'])}}" class="small-box-footer store_preorder">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <!-- ./col -->
                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning" style="background-color: #00a65a">
                                                <div class="inner">
                                                    <h3>{{($storeDetail['total_individual_kyc']) ? $storeDetail['total_individual_kyc'] : 0}}</h3>
                                                    <p>Individual Kyc </p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="{{route('support-admin.store-individual-kyc',$storeDetail['store_code'])}}" class="small-box-footer store_individual_kyc">More info <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info" style="background-color: #00c0ef">
                                                <div class="inner">
                                                    <h3>{{($storeDetail['total_firm_kyc']) ? $storeDetail['total_firm_kyc'] : 0}}</h3>
                                                    <p>Firm Kyc </p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="{{route('support-admin.store-firm-kyc',$storeDetail['store_code'])}}" class="small-box-footer store_firm_kyc">More info <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success" style="background-color:yellowgreen">
                                                <div class="inner">
                                                    <h3>{{($storeDetail['total_withdraw_request']) ? $storeDetail['total_withdraw_request'] : 0}}</h3>
                                                    <p>Withdraw</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="{{route('support-admin.store-withdraw',$storeDetail['store_code'])}}" class="small-box-footer store_withdraw">More info <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning" style="background-color: #0d6aad">
                                                <div class="inner">
                                                    <h3>{{($storeDetail['total_misc_payment']) ? $storeDetail['total_misc_payment'] : 0}}</h3>
                                                    <p>Payment </p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="{{route('support-admin.store-payment',$storeDetail['store_code'])}}" class="small-box-footer store_payment">More info <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info" style="background-color: #00a65a">
                                                <div class="inner">
                                                    <h3>{{($storeDetail['total_statement']) ? $storeDetail['total_statement'] : 0}}</h3>
                                                    <p>Statement </p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="{{route('support-admin.store-transaction-statement',$storeDetail['store_code'])}}" class="small-box-footer transaction_statement">More info <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success" style="background-color: #00c0ef">
                                                <div class="inner">
                                                    <h3>{{($storeDetail['total_investment']) ? $storeDetail['total_investment'] : 0}}</h3>
                                                    <p>Investment </p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="{{route('support-admin.store-investment',$storeDetail['store_code'])}}" class="small-box-footer store_investment">More info <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success" style="background-color: yellowgreen">
                                                <div class="inner">
                                                    <h3>{{(($storeDetail['total_orders']) ? $storeDetail['total_orders'] : 0) +
                                                    (($storeDetail['total_preorders']) ? $storeDetail['total_preorders'] : 0)}}
                                                    </h3>
                                                    <p>Store Total Orders</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-bag"></i>
                                                </div>
                                                    <p class="small-box-footer ">Total store Order: {{(($storeDetail['total_orders']) ?  $storeDetail['total_orders'] : 0)}}  </p>
                                                    <p class="small-box-footer store_preorder"> Total store preorder : {{($storeDetail['total_preorders']) ? $storeDetail['total_preorders'] : 0}} </p>

                                                     <a href="{{route('support-admin.store-order',$storeDetail['store_code'])}}"
                                                       class="small-box-footer store_order">More info <i class="fas fa-arrow-circle-right"></i>
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="background-color: #ecf0f5;">
                <div class="row">
                    <div class="col-md-12" id="store_detail">
                        {{--                                      main content--}}
                    </div>
                </div>
            </div>


        </section>
    </div>
@endsection

@push('scripts')
    @include('SupportAdmin::stores.partials.scripts');
@endpush


