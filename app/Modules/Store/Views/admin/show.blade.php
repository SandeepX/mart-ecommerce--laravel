@extends('Admin::layout.common.masterlayout')
@push('css')
<style>
    input[type=checkbox] {
        transform: scale(1.5);
    }

    /*change radio */
    div.options > label > input {
        visibility: hidden;
    }

    div.options > label {
        display: block;
        margin: 20px 0 20px -10px;
        padding: 0 0 20px 0;
        height: 20px;
        width: 150px;

    }

    div.options > label > img {
        display: inline-block;
        padding: 0px;
        height:30px;
        width:30px;
        background: none;
    }

    div.options > label > input:checked +img {
        background: url(http://cdn1.iconfinder.com/data/icons/onebit/PNG/onebit_34.png);
        background-repeat: no-repeat;
        background-position:center center;
        background-size:30px 30px;
    }
 /*End Ratio css*/

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

  @include('Admin::layout.partials.flash_message')

    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=>'Show Store Details',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
    <section class="content">

        <div class="row">

            <div id="showFlashMessage"></div>
            <br>
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
{{--                                            <div class="col-md-3 col-lg-4">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="control-label">Company Type:</label>--}}
{{--                                                    <p>{{$store['store_type']}}</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-3 col-lg-4">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="control-label">Registration Type:</label>--}}
{{--                                                    <p>{{$store['registration_type']}}</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Store Owner:</label>
                                                    <p>{{$store['store_owner']}}</p>
                                                </div>
                                            </div>
{{--                                            <div class="col-md-3 col-lg-4">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="control-label">Store Size:</label>--}}
{{--                                                    <p>{{$store['store_size']}}</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3 col-lg-4">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="control-label">Store Established Date:</label>--}}
{{--                                                    <p>{{$store['store_established_date']}}</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

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

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Store Status</label><br/>
                                                    @if($store['status']=="approved")
                                                        <p style="color: green"  >{{ucfirst($store['status'])}}</p>
                                                    @elseif($store['status']=="rejected")
                                                        <p style="color: red"  >{{ucfirst($store['status'])}}</p>
                                                    @else
                                                    <p style="color: purple"  >{{ucfirst($store['status'])}}</p>
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
                                                    <label class="control-label">Contact Mobile:</label>
                                                    <p>{{$store['contact_details']['contact_mobile']}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Contact Email:</label>
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
                        <div class="col-md-12">

                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <a href="javascript:void(0)">
                                            <b>SECTION V : PACKAGE DETAILS</b>
                                        </a>

                                    </h4>

                                    <div class="pull-right" style="margin-top: -15px;margin-left: 10px;">
                                        <a id="storeUpdatePackage" data-href="{{route('admin.store.package.update.form',$store['store_code'])}}" style="border-radius: 0px; "
                                           class="btn btn-sm btn-primary" data-toggle="modal" data-target="#storePackageUpdate">
                                            <i class="fa fa-gears"></i>
                                            Update Package
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse2" class="collapse show">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Store Type Name:</label>
                                                    <p>{{$store['store_type_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Package Name:</label>
                                                    <p>{{$store['package_name']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Has Purchase Power:</label>
                                                    @if($store['has_purchase_power'] == 1)
                                                        <p>Yes</p>
                                                    @else
                                                        <p>No</p>
                                                    @endif
                                                </div>
                                            </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Base Investment:</label>
                                                        <p>{{$store['base_investment']}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Non Refundable Registration Charge:</label>
                                                        <p>{{$store['non_refundable_registration_charge']}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Refundable Registration Charge:</label>
                                                        <p>{{$store['refundable_registration_charge']}}</p>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @if(count($store['store_package_histories'])>0)

                        <div class="col-md-12">

                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <a href="javascript:void(0)">
                                            <b>SECTION VI : PACKAGE HISTORIES</b>
                                        </a>
                                    </h4>
                                </div>

                                <div id="collapse2" class="collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <table class="table table-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Store Type</th>
                                                        <th scope="col">Store Package</th>
                                                        <th scope="col">From Date</th>
                                                        <th scope="col">To Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($store['store_package_histories'] as $packageHistory)
                                                    <tr>
                                                        <th scope="row">{{$loop->index+1}}</th>
                                                        <td>{{$packageHistory->storeType->store_type_name}}</td>
                                                        <td>{{$packageHistory->storeTypePackageHistory->package_name}}</td>
                                                        <td>{{getReadableDate(getNepTimeZoneDateTime($packageHistory->from_date))}}</td>
                                                        <td>{{getReadableDate(getNepTimeZoneDateTime($packageHistory->to_date))}}</td>
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="100%">
                                                                <p class="text-center"><b>No records found!</b></p>
                                                            </td>
                                                        </tr>
                                                    @endforelse

                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        @endif


                        @if($store['status']=="rejected")

                            <div class="col-md-12">

                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>History</b>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Status:</label>
                                                        <p>{{ucfirst($store['status'])}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Remarks:</label>
                                                        <p>{{$store['remarks']}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        @endif





                        @can('Change Status of Unapproved Store')

                            @if($store['status']=="pending" or $store['status']=="rejected")
                                <div class="col-md-12">
                                    <div clas="row">
                                        <form action="{{route('admin.store.update.status',[$storeCode=$store['store_code']])}}" method="post">
                                            @csrf
                                            <div class="options">
                                                <label  title="item1"  >
                                                    <input id="accept_check" value="accept"  type="radio" name="store_status" value="0" onclick="showHideRegistration()"/>
                                                    Accept
                                                    <img  />

                                                </label>
                                                <label  title="item2" >
                                                    <input name="store_status" id="reject_check" value="reject" onclick="showHideRemarks()" type="radio" name="foo" value="1" />
                                                    Reject
                                                    <img />
                                                </label>
                                            </div>

                                            <div id="remarks" style="display: none">
                                                Remark:
                                                <textarea style="width: 50%; margin-bottom: 10px;" class="form-control" id="remarks-input" name="remarks" rows="4" cols="50"></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-success" id="submit-btn">Submit</button>

                                        </form>
                                    </div>

                                </div>
                            @endif
                        @endcan
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

<div class="modal fade" id="storePackageUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $('#storeUpdatePackage').click(function(e) {
                e.preventDefault();
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('data-href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });
        });
    </script>
    @includeIf('Store::admin.script');
@endpush
