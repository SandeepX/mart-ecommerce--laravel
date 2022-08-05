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
    'sub_title'=>'Show Vendor Details',
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
                                            <b>SECTION I : VENDOR DETAILS</b>
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
                                                    <label class="control-label">Vendor Type:</label>
                                                    <p>{{$vendor['vendor_type']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Registration Type:</label>
                                                    <p>{{$vendor['registration_type']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Vendor Name:</label>
                                                    <p>{{$vendor['vendor_name']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Vendor Code:</label>
                                                    <p>{{$vendor['vendor_code']}}</p>
                                                </div>
                                            </div>


                                            @if(isset($vendor['pan_no']))

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Pan:</label>
                                                    <p>{{$vendor['pan_no']}}</p>
                                                </div>
                                            </div>

                                            @else

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Vat:</label>
                                                        <p>{{$vendor['vat_no']}}</p>
                                                    </div>
                                                </div>

                                            @endif

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Vendor Logo</label><br/>
                                                    @if(isset($vendor['vendor_logo']))
                                                        <img src="{{$vendor['vendor_logo']}}"
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
                                                    <p>{{$vendor['location_details']['province']['location_name']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">District:</label>
                                                    <p>{{$vendor['location_details']['district']['location_name']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Municipality:</label>
                                                    <p>{{$vendor['location_details']['municipality']['location_name']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Ward:</label>
                                                    <p>{{$vendor['location_details']['ward']['location_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Land Mark:</label>
                                                    <p>{{$vendor['location_details']['landmark']['name']}}</p>
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
                                                    <label class="control-label">Conatct Name:</label>
                                                    <p>{{$vendor['contact_details']['contact_person']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Conatct Landline:</label>
                                                    <p>{{$vendor['contact_details']['contact_phone']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Conatct Mobile:</label>
                                                    <p>{{$vendor['contact_details']['contact_mobile']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Conatct Email:</label>
                                                    <p>{{$vendor['contact_details']['contact_email']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Conatct Fax:</label>
                                                    <p>{{$vendor['contact_details']['contact_fax']}}</p>
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
                                                    <p>{{$vendor['vendor_user_details']['name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Email:</label>
                                                    <p>{{$vendor['vendor_user_details']['email']}}</p>
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



@endsection
