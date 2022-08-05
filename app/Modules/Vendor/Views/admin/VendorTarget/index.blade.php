
@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        .box-color {
            float: left;
            height: 15px;
            width: 10px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #ff667a ;
        }

        .warning-color {
            background-color:  #f5c571 ;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #F21805;
            -webkit-transition: .4s;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #50C443;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #50C443;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }


    </style>
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.vendorTarget.index')}}" method="get">
                                <div class="col-xs-3">
                                    <label for="location_name"> Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $filterParameters['name'] }}">
                                </div>


                                <div class="col-xs-3">
                                    <label for="location_name">Location Name</label>
                                    <input type="text" class="form-control" name="location_name" id="location_name" value="{{ $filterParameters['location_name'] }}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="vendor_name">vendor Name</label>
                                    <input type="text" class="form-control" name="vendor_name" id="vendor_name" value="{{ $filterParameters['vendor_name'] }}">
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="is_active">Is Active</label>
                                        <select name="is_active" class="form-control" id="is_active">
                                            <option value="" {{is_null($filterParameters['is_active'])?'selected':''}}>All</option>
                                            <option value="1" {{(isset($filterParameters['is_active']) && $filterParameters['is_active'] == 1)?'selected':''}}>Active</option>
                                            <option value="0" {{ (isset($filterParameters['is_active']) && $filterParameters['is_active'] == 0)?'selected':''}}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                        <label for="status">status</label>
                                        <select name="status" class="form-control" id="status" >
                                            <option value="">Select All </option>
                                            <option value="pending" {{($filterParameters['status'] =="pending")?'selected':''}} >Pending</option>
                                            <option value="processing" {{($filterParameters['status'] =="processing")?'selected':''}}>Processing</option>
                                            <option value="rejected" {{($filterParameters['status'] =="rejected")?'selected':''}}>Rejected</option>
                                            <option value="accepted" {{($filterParameters['status'] =="accepted")?'selected':''}}>Accepted</option>
                                        </select>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="start_date">Start  Date </label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                               value="{{($filterParameters['start_date'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="end_date">End Date </label>
                                        <input type="date" class="form-control" name="end_date" id="end_date"
                                               value="{{($filterParameters['end_date'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Vendor Name</th>
                                    <th>Province Code</th>
                                    <th>District Code</th>
                                    <th>Municipality Code</th>
                                    <th>Start date</th>
                                    <th>End Date</th>
                                    <th>Is Active</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($vendorTargets as $key => $value)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->vendor->vendor_name}}</td>
                                        <td>{{$value->province->location_name }}</td>
                                        <td>{{$value->district->location_name}}</td>
                                        <td>{{$value->municipality->location_name }}</td>
                                        <td>{{$value->start_date}}</td>
                                        <td>{{$value->end_date}}</td>
                                        <td>
                                            <label class="switch">
                                                <input class="toggleStatus" data-VTMcode="{{$value->vendor_target_master_code }}" type="checkbox" {{($value->is_active) === 1 ?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>

                                        <!-- Button trigger modal -->


                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs changeStatus" data-toggle="modal" data-id="{{$value->vendor_target_master_code }}" data-currentStatus="{{($value->status)}}" data-target="#exampleModal">
                                                {{ucfirst($value->status)}}
                                            </button>
                                        </td>

                                        <td>
                                            @canany('Show Target Incentative')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.vendor-target-incentative.show',$value->vendor_target_master_code  ),'show Target Incentative', 'eye','primary')!!}
                                            @endcanany
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
                            {{$vendorTargets->links()}}
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>




    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Change Status of Vendor Target</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="get" id="vendorTarget" action="{{route('admin.vendorTarget.change-VTM-status')}}" >
                        @csrf
                        <input type="hidden" value="" name="vendor_target_master_code" id="VTMCode" />

                        <div class="form-group">
                            <label class="control-label">Change Status</label>
                            <select class="form-control input-sm" value=""  name="status" id="VTM-status" required autocomplete="off">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="changeVendorTargetStatus" class="btn btn-success">Change</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>
        $(document).ready(function (){

            $('.toggleStatus').change(function(event) {
                event.preventDefault();
                var status = $(this).prop('checked') === true ? 1 : 0;
                var VTMcode = $(this).attr('data-VTMcode');

                if(status===1){
                    var name = 'Active';
                }else{
                    var name = 'Inactive';
                }

                Swal.fire({
                    title: 'Are you sure you want to change status to ' + name + '?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10em',
                    width:'500px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: '{{route('admin.vendorTarget.changeStaus')}}',
                            data: {'status': status, 'VTMcode': VTMcode},
                            success: function(data){
                                //console.log(data.success);
                                location.reload();
                            }
                        })
                    } else if (result.isDenied) {
                        if (status === 0) {
                            $(this).prop('checked', true);
                        } else if (status === 1) {
                            $(this).prop('checked', false);
                        }
                    }
                })

            })


            $('.changeStatus').on('click',function (e){
                e.preventDefault();
                $("#VTMCode").val($(this).attr('data-id'));
                $("#VTM-status").val($(this).attr('data-currentStatus'));
            });

            $('#changeVendorTargetStatus').on('click',function (e){
                e.preventDefault();
                var VTMcode = $('#VTMCode').val();
                var status = $('#VTM-status').val();

                Swal.fire({
                    title: 'Do you want ' + status + '?',
                    width: 500,
                    padding: '5em',
                    confirmButtonText: `Okay`,
                    showDenyButton: true,
                    denyButtonText: `Don't change`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#exampleModal').modal('hide');
                        $.ajax({
                            type: "GET",
                            url: '{{route('admin.vendorTarget.change-VTM-status')}}',
                            data: {
                                status: status,
                                VTMcode: VTMcode,
                            },
                            success: function(data){
                                location.reload();
                            }
                        });

                    }else if (result.isDenied) {
                        $('#exampleModal').modal('hide');
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                });
            });

        })
    </script>


@endpush
