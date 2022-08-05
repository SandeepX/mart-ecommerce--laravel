@extends('Admin::layout.common.masterlayout')
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
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=>'Show Warehouse Details',
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
                                            <b>SECTION I : WAREHOUSE DETAILS</b>
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
                                                    <label class="control-label">Warehouse Name:</label>
                                                    <p>{{$warehouse['warehouse_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Warehouse Code:</label>
                                                    <p>{{$warehouse['warehouse_code']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Warehouse Type:</label>
                                                    <p>{{ucwords($warehouse['warehouse_type'])}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Remarks:</label>
                                                    <p>{{$warehouse['remarks']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">{{ucwords($warehouse['pan_vat_type'])}} No:</label>
                                                    <p>{{$warehouse['pan_vat_no']}}</p>
                                                </div>
                                            </div>


                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Warehouse Logo:</label>
                                                    <br/>
                                                    @if(isset($warehouse['warehouse_logo']))
                                                        <img src="{{$warehouse['warehouse_logo']}}"
                                                             alt="Warehouse Logo" width="100" height="60" style="object-fit: cover">
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
                                                    <p>{{$warehouse['location_details']['province']['location_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">District:</label>
                                                    <p>{{$warehouse['location_details']['district']['location_name']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Municipality:</label>
                                                    <p>{{$warehouse['location_details']['municipality']['location_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Ward:</label>
                                                    <p>{{$warehouse['location_details']['ward']['location_name']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Landmark</label>
                                                    <p>{{$warehouse['location_details']['landmark']['name']}}</p>
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
                                                    <label class="control-label">Contact Name:</label>
                                                    <p>{{$warehouse['contact_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Contact Email:</label>
                                                    <p>{{$warehouse['contact_email']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Contact Phone 1:</label>
                                                    <p>{{$warehouse['contact_phone_1']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Contact Phone 2:</label>
                                                    <p>{{$warehouse['contact_phone_2']}}</p>
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
                                            @foreach($warehouse['warehouse_user_details'] as  $user)
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">User Name:</label>
                                                    <p>{{$user['name']}}</p>
                                                </div>
                                            </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Email:</label>
                                                            <p>{{$user['email']}}</p>
                                                        </div>
                                                    </div>
                                            @endforeach
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



@endsection
