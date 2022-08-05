@extends('admin.common.masterlayout')
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
    .text-muted{
        margin-left: 0px;
    }
    .box-title{
        font-weight: 600;
       text-transform: uppercase;
    }
</style>
@endpush


@section('content')
    <div class="content-wrapper">
    @include('admin.partials.breadcrumb',
    [
    'page_title'=>App\Helpers\ViewHelper::formatWords($base_route,true),
    'sub_title'=>'Customer Details',
    'icon'=>'home',
    'sub_icon'=>$sub_icon,
    'manage_url'=>$base_route
    ])
    <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        @include('admin.partials.flash_message')
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h3 style="font-size: 15px;" class="box-title">{{$customer->full_name_nepali or '---'}} ({{$customer->full_name_english ?  $customer->full_name_english : ' '}})</h3>

                                             <span style="font-size: 12px;" class="label label-{{$customer->area ? 'primary' : 'warning'}}">
                                                 Area : {{$customer->area ? $customer->area->area_name.' / '. $customer->area->area_no : 'No Area'}}
                                             </span>
                                            &nbsp; &nbsp;
                                            <span style="font-size: 12px;" class="label label-{{$customer->resident_type ? 'warning' : 'primary'}}">
                                                  {{$customer->resident_type == 'old' ? 'Old Resident' : 'New Resident'}}
                                             </span>

                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <img style="margin-top:40px;width:130px;height:145px;border-radius:1% !important" src="{{url('uploads/customer_photos/'.$customer->customer_photo)}}" alt="No Image" class="rounded-circle img-fluid"/>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <ul class="list-group list-group-unbordered">
                                                            <li class="list-group-item">
                                                                <b>Citizenship No.</b> <a class="pull-right">{{$customer->citizenship_no or '---'}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Address</b> <a class="pull-right">{{$customer->address or '---'}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Ward No.</b> <a class="pull-right">{{$customer->ward_no or '--'}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Tole Name</b> <a class="pull-right">{{$customer->tole_name}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Father's Name</b> <a class="pull-right">{{$customer->father_name}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Grand Father/Father-in-Law's Name</b> <a class="pull-right">{{$customer->gfather_name}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Spouse Name</b> <a class="pull-right">{{$customer->spouse_name}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Mobile / Phone No.</b>
                                                                <a class="pull-right">
                                                                    {{$customer->contact_no}} {{$customer->phone_no ? ' / '.$customer->phone_no : ''}}</a>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            {{--<h3 class="box-title">Personal Details - II</h3>--}}
                                            <span style="font-size: 11px;" class="label label-{{$customer->registration_type ? 'danger' : 'success'}}">
                                                Registration Type : {{$customer->registration_type  ? $customer->registration_type->registration_type : 'No Registration Type'}}
                                             </span>
                                            &nbsp;
                                            <span style="font-size: 11px;" class="label label-{{$customer->reg_no ? 'success' : 'danger'}}">
                                                Registration Number : {{$customer->reg_no  ? $customer->reg_no : 'Not Found'}}
                                             </span>
                                        </div>
                                        <div class="box-body">
                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item">
                                                    <b>Land Lord Document Ward No.</b> <a class="pull-right">{{$customer->land_proof_ward}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Kitta No.</b> <a class="pull-right">{{$customer->kitta_no}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Land Area (m<sup>2</sup>) </b> <a class="pull-right">{{$customer->land_area}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>House Area (m<sup>2</sup>)</b> <a class="pull-right">{{$customer->house_area}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>House Height (m) </b> <a class="pull-right">{{$customer->house_height}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Ghar Naksa Pass Miti</b> <a class="pull-right">{{$customer->ghar_pass_miti}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Dhara Jadan</b> <a class="pull-right">{{$customer->isMetered() ? 'Yes' : 'No'}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Dhara Jadan Miti</b> <a class="pull-right">{{$customer->dhara_jadan_miti}}</a>
                                                </li>

                                            </ul>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Water Consumption Details</h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <strong>Reason of Installment</strong>

                                            <p class="text-muted">
                                                {!! $customer->reason !!}
                                            </p>

                                            <hr>

                                            <strong>Number of Consumers  : {{$customer->consumers_no}}</strong><br>
                                            <strong></i> Daily Water Consumption (Litre)  : {{$customer->daily_consumption}}</strong>

                                            <hr>

                                            @if($customer->old_installed)
                                            <strong>
                                               Previous Installment Details
                                            </strong>

                                            <p class="text-muted">
                                                Full Name :  {!! $customer->old_name !!}<br>
                                                Tap No. :  {!! $customer->old_tap_no !!}<br>
                                                Area No. :  {!! $customer->old_area_no !!}
                                            </p>

                                            <hr>
                                            @endif

                                            <strong>
                                                Neighbour Customer Details
                                            </strong>

                                            <p class="text-muted">
                                                Full Name :  {!! $customer->neighbour_name !!}<br>
                                                Customer No. :  {!! $customer->neighbour_customer_no !!}<br>
                                                Area No. :  {!! $customer->neighbour_area_no !!}
                                            </p>
                                            <hr>
                                        </div>
                                        <!-- /.box-body -->
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


