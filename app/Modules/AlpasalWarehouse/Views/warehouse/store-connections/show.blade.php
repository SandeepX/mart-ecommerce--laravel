@extends('AdminWarehouse::layout.common.masterlayout')
@push('css')
    <style>
        input[type=checkbox] {
            transform: scale(1.5);
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 4px 10px;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        hr {
            margin-top: 6px;
            margin-bottom: 8px;
            border: 0;
            border-top: 4px solid #eee;
        }

        .text-muted {
            margin-left: 0px;
        }

        .box-title {
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
@endpush


@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=>'Show Store Details',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route),
    ])
        @can('View WH Store Connection')
            <!-- Main content -->
            <section class="content">
                @include('AdminWarehouse::layout.partials.flash_message')
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
                                                    <b>SECTION I : STORE DETAILS</b>
                                                </a>
                                            </h4>
                                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                                <a href="{{ route($base_route) }}" style="border-radius: 0px; "
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
                                                            <label class="control-label">Store Name:</label>
                                                            <p>{{$store['store_name']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Store Code:</label>
                                                            <p>{{$store['store_code']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Company Type:</label>
                                                            <p>{{$store['store_type']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Registration Type:</label>
                                                            <p>{{$store['registration_type']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Store Owner:</label>
                                                            <p>{{$store['store_owner']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Store Size:</label>
                                                            <p>{{$store['store_size']}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Store Established Date:</label>
                                                            <p>{{$store['store_established_date']}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">{{ucwords($store['pan_vat_type'])}} No:</label>
                                                            <p>{{$store['pan_vat_no']}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Store Logo</label><br/>
                                                            @if(isset($store['store_logo']))
                                                                <img src="{{$store['store_logo']}}"
                                                                     alt="Store Logo" width="100" height="60" style="object-fit: cover">
                                                            @endif
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
                                                    <b>SECTION II : LOCATION DETAILS</b>
                                                </a>
                                            </h4>

                                        </div>
                                        <div id="collapse2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Province:</label>
                                                            <p>{{$store['location_details']['province']['location_name']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">District:</label>
                                                            <p>{{$store['location_details']['district']['location_name']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Municipality:</label>
                                                            <p>{{$store['location_details']['municipality']['location_name']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Ward:</label>
                                                            <p>{{$store['location_details']['ward']['location_name']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Land Mark:</label>
                                                            <p>{{$store['location_details']['landmark']['name']}}</p>
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
                                                    <b>SECTION III : CONTACT DETAILS</b>
                                                </a>
                                            </h4>

                                        </div>
                                        <div id="collapse2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Contact Landline:</label>
                                                            <p>{{$store['contact_details']['contact_phone']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Conatct Mobile:</label>
                                                            <p>{{$store['contact_details']['contact_mobile']}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Conatct Email:</label>
                                                            <p>{{$store['contact_details']['contact_email']}}</p>
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
                                                    <b>SECTION IV : USER DETAILS</b>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">User Name:</label>
                                                            <p>{{$store['store_user_details']['name']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Email:</label>
                                                            <p>{{$store['store_user_details']['email']}}</p>
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
        @endcan
    </div>



@endsection
