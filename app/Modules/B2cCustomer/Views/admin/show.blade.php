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
            @include('Admin::layout.partials.flash_message')
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
                                                <b>SECTION I : B2C User LOGIN DETAILS</b>
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
                                                        <p>{{$userB2C->name}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Login Email:</label>
                                                        <p>{{$userB2C->login_email}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Login Phone:</label>
                                                        <p>{{$userB2C->login_phone}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">User Type:</label>
                                                        <p>{{$userB2C->userType->user_type_name}}</p>
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
                                                <b>SECTION II : B2C User Created Details</b>
                                            </a>
                                        </h4>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Created By:</label>
                                                        <p>{{$userB2C->userCreatedBy->name}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Last Login Ip:</label>
                                                        <p>{{$userB2C->last_login_ip}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Last Login At:</label>
                                                        <p>@if($userB2C->last_login_at){{getReadableDate(getNepTimeZoneDateTime($userB2C->last_login_at))}}@endif</p>
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
                                                <b>SECTION III : B2C User Document Details</b>
                                            </a>
                                        </h4>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <table class="table table-bordered " cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Document Name</th>
                                                        <th>Document Number</th>
                                                        <th>Document</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>


                                                    @forelse($userDocs as $i=>$doc)
                                                        <tr>
                                                            <td>{{++$i}}</td>
                                                            <td>{{is_null($doc->doc_name)?'N/A':$doc->doc_name}}</td>
                                                            <td>{{is_null($doc->doc_number)?'N/A':$doc->doc_number}}</td>
                                                            <td>
                                                                <a href="{{$doc->getDocumentImagePath()}}" target="_blank">
                                                                    <img src="{{$doc->getDocumentImagePath()}}" width="80px;" height="50px;" alt="Document">
                                                                </a>
                                                            </td>
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


                            <div class="col-md-12">

                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>SECTION IV :B2C User Registration Details</b>
                                            </a>
                                        </h4>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Status:</label>
                                                        <p><span class="label label-info">{{ucwords($userRegistrationStatus->status)}}</span></p>
                                                    </div>
                                                </div>

{{--                                                <div class="col-md-3 col-lg-4">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="control-label">Location Assigned:</label>--}}
{{--                                                        <p>{{ isset($userRegistrationStatus->getLocationName->location_name) ? ($userRegistrationStatus->getLocationName->location_name):'N/A'}}</p>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                                @if(isset($userB2C->ward_code))
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Permanent Location:</label>
                                                            <p>{{ isset($userB2C) ? ($userB2C->getFullLocationPathByLocation($userB2C->permanentLocation)):'N/A'}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(isset($userB2C->temporary_ward))
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Temporary Location:</label>
                                                            <p>{{ isset($userB2C) ? ($userB2C->getFullLocationPathByLocation($userB2C->temporaryLocation)):'N/A'}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Remarks:</label>
                                                        <p>{{ isset($userRegistrationStatus->remarks) ? ucfirst($userRegistrationStatus->remarks) :'N/A'}}</p>
                                                    </div>
                                                </div>


                                            </div>
{{--                                            @if($userRegistrationStatus->status!="approved" )--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <div clas="row">--}}
{{--                                                        <form class="col-md-6" id="form-status" action="" method="post">--}}
{{--                                                            @csrf--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label class="control-label">B2C User Registration Status</label>--}}
{{--                                                                <select class="form-control select2" name="status" id="b2c-user-status">--}}
{{--                                                                    @if($userRegistrationStatus->status !='pending')--}}
{{--                                                                        <option value="pending" {{($userRegistrationStatus->status =='pending') ? "selected" : ''}}>Pending</option>--}}
{{--                                                                    @endif--}}
{{--                                                                    <option value="processing"{{($userRegistrationStatus->status =='processing') ? "selected" : ''}}>Processing</option>--}}
{{--                                                                    <option value="rejected"  {{($userRegistrationStatus->status =='rejected') ? "selected" : ''}}>Rejected</option>--}}
{{--                                                                    <option value="approved" {{($userRegistrationStatus->status == 'approved') ? "selected" : ''}}>Approved</option>--}}
{{--                                                                </select>--}}
{{--                                                            </div>--}}

{{--                                                            <div id="remarks"  style="display: none">--}}
{{--                                                                <label class="control-label"> Remarks:</label>--}}
{{--                                                                <textarea style="width: 50%; margin-bottom: 10px;" class="form-control" id="remarks-input" name="remarks" rows="4" cols="50">{{$userRegistrationStatus->remarks}}</textarea>--}}
{{--                                                            </div>--}}

{{--                                                            <div class="col-sm-12">--}}
{{--                                                                <button type="submit" class="btn btn-success" id="submit-btn">Submit</button>--}}
{{--                                                            </div>--}}
{{--                                                        </form>--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                            @endif--}}
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

@push('scripts')
    <script>

        // $(document).ready(function() {
        //
        //     let b2CUserRegistrationStatus = $('#b2c-user-status');
        //
        //     b2CUserRegistrationStatus.trigger('change');
        //
        //     b2CUserRegistrationStatus.on('change',function (e){
        //         e.preventDefault();
        //         let status = $(this).val();
        //
        //         if(status=='rejected'){
        //             $('#remarks').show();
        //             $('#remarks-input').prop('required',true);
        //         }else{
        //             $('#remarks-input').prop('required',false);
        //             $('#remarks').hide();
        //         }
        //
        //     });
        // });

    </script>
@endpush



