@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> "Show the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">


                            <div class="box-body">

                                <div class="col-md-12">

                                    <div class="card card-default">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a href="javascript:void(0)">
                                                    <b>SECTION I : USER LOGIN DETAILS</b>
                                                </a>
                                            </h4>
                                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                                <a href="{{ route($base_route . '.index') }}" style="border-radius: 0px; "
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fa fa-list"></i>
                                                    List of {{ formatWords($title, true) }}
                                                </a>
                                            </div>

                                        </div>
                                        <div id="collapse2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Name:</label>
                                                            <p>{{$user->name}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Login Email:</label>
                                                            <p>{{$user->login_email}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Login Phone:</label>
                                                            <p>{{$user->login_phone}}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">

                                    <div class="card card-default">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a href="javascript:void(0)">
                                                    <b>SECTION II : User Type Detail</b>
                                                </a>
                                            </h4>

                                        </div>
                                        <div id="collapse2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">User Type:</label>
                                                            <p>{{$user->userType->user_type_name}}</p>
                                                        </div>
                                                    </div>

                                                    @if($user->userType->slug=='store')
                                                        <div class="col-md-3 col-lg-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Store Name:</label>
                                                                <p>
                                                                    {{$user->store->store_name}}
                                                                    (
                                                                      <a href="{{route('admin.stores.show',$user->store->store_code)}}" title="Show Store" target="_blank"> {{$user->store->store_code}} </a>
                                                                    )
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($user->userType->slug=='vendor')
                                                        <div class="col-md-3 col-lg-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Vendor Name:</label>
                                                                <p>
                                                                    {{$user->vendor->vendor_name}}
                                                                    (
                                                                    <a href="{{route('admin.vendors.show',$user->vendor->vendor_code)}}" title="Show Vendor" target="_blank"> {{$user->vendor->vendor_code}} </a>
                                                                    )
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($user->userType->slug=='warehouse-admin' || $user->userType->slug=='warehouse-user')
                                                        <div class="col-md-3 col-lg-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Warehouse Name:</label>
                                                                <p>
                                                                    {{$user->warehouseUser->warehouse->warehouse_name}}
                                                                    (
                                                                    <a href="{{route('admin.warehouses.show',$user->warehouseUser->warehouse->warehouse_code)}}" title="Show Vendor" target="_blank"> {{$user->warehouseUser->warehouse->warehouse_code}} </a>
                                                                    )
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="card card-default">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a href="javascript:void(0)">
                                                    <b>SECTION II : User Created Details</b>
                                                </a>
                                            </h4>

                                        </div>
                                        <div id="collapse2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Created By:</label>
                                                            <p>{{$user->userCreatedBy->name}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Last Login Ip:</label>
                                                            <p>{{$user->last_login_ip}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Last Login At:</label>
                                                            <p>{{getReadableDate($user->last_login_at)}}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>
                    <!--/.col (left) -->

                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
    </div>

    </div>



@endsection

