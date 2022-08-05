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
                                                <b>SECTION I : Sales Manager Details</b>
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
                                                        <p>{{$salesManager->manager_name}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Login Email:</label>
                                                        <p>{{$salesManager->user->login_email}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Login Phone:</label>
                                                        <p>{{$salesManager->user->login_phone}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Email:</label>
                                                        <p>{{$salesManager->manager_email}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Phone:</label>
                                                        <p>{{$salesManager->manager_phone_no}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">User Type:</label>
                                                        <p>{{$salesManager->user->userType->user_type_name}}</p>
                                                    </div>
                                                </div>
                                                @if($salesManager->referral_code && $salesManager->status==='approved')
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Referral Code:</label>
                                                            <p>{{$salesManager->referral_code}}</p>
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
                                                <b>SECTION II : Sales Manager Created Details</b>
                                            </a>
                                        </h4>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Created By:</label>
                                                        <p>{{$salesManager->user->userCreatedBy->name}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Last Login Ip:</label>
                                                        <p>{{$salesManager->user->last_login_ip}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Last Login At:</label>
                                                        <p>@if($salesManager->user->last_login_at){{getReadableDate(getNepTimeZoneDateTime($salesManager->last_login_at))}}@endif</p>
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
                                                <b>SECTION III : Sales Manager Document Details</b>
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

                                                    @forelse($managerDocs as $i=>$doc)
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
                                                <b>SECTION IV :Sales Manager Registration Details</b>
                                            </a>
                                        </h4>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Status:</label>
                                                        <p>{{ucwords($salesManager->status)}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Location Assigned:</label>
                                                        <p>{{ isset($salesManager->getLocationName->location_name) ? ($salesManager->getLocationName->location_name):'N/A'}}</p>
                                                    </div>
                                                </div>
                                                @if(isset($salesManager->ward_code))
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Permanent Location:</label>
                                                        <p>{{ isset($salesManager) ? ($salesManager->permanent_full_location):'N/A'}}</p>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(isset($salesManager->temporary_ward_code))
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Temporary Location:</label>
                                                        <p>{{ isset($salesManager) ? ($salesManager->temporary_full_location):'N/A'}}</p>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Remarks:</label>
                                                        <p>{{ isset($salesManager->remarks) ? ucfirst($salesManager->remarks) :'N/A'}}</p>
                                                    </div>
                                                </div>


                                            </div>

                                            @can('Change Manager Status')
                                                 @if($salesManager->status!="approved" )
                                                <div class="col-md-12">
                                                    <div clas="row">
                                                        <form class="col-md-6" id="form-status" action="{{route('admin.salesmanager.change.status',$salesManager->manager_code)}}" method="post">
                                                               @csrf
                                                                <div class="form-group">
                                                                    <label class="control-label">Sales Manager Status</label>
                                                                    <select class="form-control select2" name="status" id="sales-manager-status">
                                                                        @if($salesManager->status !='pending')
                                                                            <option value="pending" {{($salesManager->status =='pending') ? "selected" : ''}}>Pending</option>
                                                                        @endif
                                                                        <option value="processing"{{($salesManager->status =='processing') ? "selected" : ''}}>Processing</option>
                                                                        <option value="rejected"  {{($salesManager->status =='rejected') ? "selected" : ''}}>Rejected</option>
                                                                        <option value="approved" {{($salesManager->status == 'approved') ? "selected" : ''}}>Approved</option>
                                                                    </select>
                                                                </div>

                                                            <div class="form-group " id="assigned_area_province">
                                                                <label class="control-label">Province</label>
                                                                <select class="form-control select2" name="province" id="province" >

                                                                </select>
                                                            </div>

                                                            <div class="form-group " id="assigned_area_district">
                                                                <label class="control-label">District</label>
                                                                <select class="form-control select2"  name="district" id="district" >

                                                                </select>
                                                            </div>

                                                            <div class="form-group " id="assigned_area_municipality">
                                                                <label class="control-label">Assign Area</label>
                                                                <select class="form-control select2" name="assigned_area_code" id="area" >

                                                                </select>
                                                            </div>


                                                            <div id="remarks"  style="display: none">
                                                                 <label class="control-label"> Remarks:</label>
                                                                <textarea style="width: 50%; margin-bottom: 10px;" class="form-control" id="remarks-input" name="remarks" rows="4" cols="50">{{$salesManager->remarks}}</textarea>
                                                            </div>

                                                             <div class="col-sm-12">
                                                               <button type="submit" class="btn btn-success" id="submit-btn">Submit</button>
                                                             </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            @endif
                                            @endcan
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

@push('scripts')
<script>

    $(document).ready(function() {

        $('#assigned_area_province').hide();
        $('#assigned_area_district').hide();
        $('#assigned_area_municipality').hide();

        $.ajax({
            url:"{{route('admin.vendorTarget.get-province')}}",
            type:"get",
            data: {
                _token: '{{csrf_token()}}'
            },
            success:function(data) {
                //console.log(data);
                $('#province').append(data);
            }
        })

        let province = $('#province');

        province.trigger('change');

        province.on('change',function(e){
            e.preventDefault();
            $('#assigned_area_district').show();
            $('#district').empty();
            $('#area').empty();
            var provinceCode = $('#province').val();
            $.ajax({
                url:"{{route('admin.vendorTarget.get-district')}}",
                type:"get",
                data: {
                    provinceCode:provinceCode,
                    _token: '{{csrf_token()}}'
                },
                success:function(data) {
                    //console.log(data);
                    $('#district').append(data);
                }
            })
        });

        let district = $('#district');

        district.trigger('change');

        district.on('change',function(e){
            e.preventDefault();
            $('#assigned_area_municipality').show();
            $('#area').empty();
            var districtCode = $('#district').val();

            $.ajax({
                url:"{{route('admin.vendorTarget.get-muncilipality')}}",
                type:"get",
                data: {
                    districtCode:districtCode,
                    _token: '{{csrf_token()}}'
                },
                success:function(data) {
                    //console.log(data);
                    $('#area').append(data);
                }
            })

        });



        let salesManageStatus = $('#sales-manager-status');

        salesManageStatus.trigger('change');

        salesManageStatus.on('change',function (e){
           e.preventDefault();
           let status = $(this).val();
           //console.log(status);

           if(status=='rejected'){
               $('#area').empty();
               $('#remarks').show();
               $('#remarks-input').prop('required',true);
               $('#assigned_area').hide();
               $('#assigned_area_province').hide();
               $('#assigned_area_district').hide();
               $('#assigned_area_municipality').hide();
           }else{

               $('#remarks-input').prop('required',false);
               $('#remarks').hide();
           }

           if(status=='approved'){
               $('#assigned_area_province').show();
               $('#area').prop('required',true);
           }else{
               $('#area').prop('required',false);
               $('#assigned_area').hide();
               $('#assigned_area_province').hide();
               $('#assigned_area_district').hide();
               $('#assigned_area_municipality').hide();
           }

        });
    });

</script>
@endpush

