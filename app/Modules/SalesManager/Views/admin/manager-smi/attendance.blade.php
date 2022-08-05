@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #ff667a ;
        }

        .warning-color {
            background-color:  #f5c571 ;
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
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->

                    {{--                    Update Modal--}}
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-md">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!-- modal header  -->
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <strong class="modal-title" id="attendanceToday">{{(!isset($managerTodaysAttendanceDetail))?'Add':'Update'}} Manager Today Attendance Detail: {{\Carbon\Carbon::today()->format('m/d/Y')}}</strong>
                                </div>
                                <div class="modal-body">
                                    <!-- begin modal body content  -->
                                    <form action="{{route('admin.manager-smi.attendance.store',$MSMICode)}}" method="get">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Today Date:</label>
                                                <input class="form-control"  type="date" readonly value="{{\Carbon\Carbon::today()->format('Y-m-d')}}"  name="attendance_date" placeholder="Select Todays Date">
                                            </div>

                                            <div class="col-md-12">
                                                <label>Status:</label>
                                                <select class="form-control input-sm" id="status" name="status">
                                                    <option value="">--Select Attendance Status--</option>
                                                    @foreach($status as $key => $value)
                                                        <option value="{{$value}}" {{(isset($managerTodaysAttendanceDetail) && $managerTodaysAttendanceDetail['status'] == $value)? 'selected':'' }}>{{ucfirst($value)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remarks" id="remarks" placeholder="Remarks">{{(isset($managerTodaysAttendanceDetail) && $managerTodaysAttendanceDetail['remarks'])? $managerTodaysAttendanceDetail['remarks']:''}}</textarea>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <!-- modal footer  -->
                                            <button type="submit" class="btn btn-primary" style="margin:10px;">{{(!isset($managerTodaysAttendanceDetail))?'Save Attendance':'Update Attendance'}}</button>
                                        </div>
                                    </form>
                                    <!-- end modal body content  -->
                                </div>

                            </div>

                        </div>
                    </div>
                    {{--                    End Update modal--}}

                    {{--                    Edit modal--}}
                    <div class="modal fade" id="editModal" role="dialog">
                        <div class="modal-dialog modal-md">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!-- modal header  -->
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title edit"></h4>
                                </div>
                                <div class="modal-body">
                                    <!-- begin modal body content  -->
                                    <form id="editAttendanceDetail" action="" method="post">
                                        @method('put')
                                        @csrf
                                        <div class="row">

                                            <div class="col-md-12">
                                                <label>Status:</label>
                                                <select class="form-control input-sm" id="pastAttendanceEditStatus" name="status">
                                                    <option value="">--Select Attendance Status--</option>
                                                    @foreach($status as $key => $value)
                                                        <option value="{{$value}}" >{{ucfirst($value)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remarks" id="pastAttendacneEditRemarks" placeholder="Remarks"></textarea>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <!-- modal footer  -->
                                            <button type="submit"  class="btn btn-primary" style="margin:10px;">Save</button>
                                        </div>
                                    </form>
                                    <!-- end modal body content  -->
                                </div>

                            </div>

                        </div>
                    </div>
                    {{--                    End Edit modal--}}

                    {{--                    Remark modal--}}
                    <div class="modal fade" id="remarkModal" role="dialog">
                        <div class="modal-dialog modal-md">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!-- modal header  -->
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title ">Attendance Remark</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- begin modal body content  -->
                                   <p class="remark"> </p>
                                    <!-- end modal body content  -->
                                </div>

                            </div>

                        </div>
                    </div>
                    {{--                    End Remark modal--}}

                    <div class="box box-primary">
                        <div class="box-body">

                            <div class="col-xs-12">
                                <div class="panel-group">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong>
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                                {{ucfirst($managerSMI->manager->manager_name)}}
                                            </strong>

                                            @if($managerSMI->status == 'approved' && $managerSMI->is_active == 1 )
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <button class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#myModal" style="margin-top: -5px;" type="button" aria-expanded="true">
                                                      <strong><i class="fa fa-clock-o" aria-hidden="true"></i>
                                                               Daily Attendance</strong>
                                                    </button>
                                                </div>
                                            @endif

                                            <?php
                                                $status = [
                                                    'absent' =>'danger',
                                                    'present' =>'success'
                                                ]
                                            ?>

                                        </div>
                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="collapse in" style="">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            @if($managerSMI->status == 'approved' && $managerSMI->is_active==1)
                                                                <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6"><strong>Today</strong>: <span class="label  label-info"> {{(!is_null($managerTodaysAttendanceDetail))?$managerTodaysAttendanceDetail['attendance_date']:'N/A'}}</span></div>
                                                                    <div class="col-md-6 text-right"><strong>Status</strong>:<span class="label label-info">{{(!is_null($managerTodaysAttendanceDetail))?ucfirst($managerTodaysAttendanceDetail['status']):'N/A'}}</span> </div>
                                                                    <div class="col-md-12"> <strong>Remarks</strong>: {{(!is_null($managerTodaysAttendanceDetail))?ucfirst($managerTodaysAttendanceDetail['remarks']):'N/A'}}  </div>
                                                                </div>
                                                            </div>
                                                            @else
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <h3> Today's attendance of smi manager <strong>{{$managerSMI->manager->manager_name}}</strong> cannot be taken since status
                                                                            is in <strong>{{$managerSMI->status}} </strong> state and Is Active status is {{($managerSMI->is_active==1)?'Active':'InActive'}}.</h3>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <strong>
                                                Attendance Details
                                            </strong>
                                        </div>

                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="box-body">
                                                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>


                                                        @forelse($managerAttedances as $key => $datum)
                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{ date('d-M-Y',strtotime($datum['attendance_date']))}}</td>
                                                                <td><span class="label label-{{$status[$datum->status]}}">{{ucfirst($datum->status)}}</span> </td>
                                                                <td>
                                                                    <button id="edit" data-toggle="modal" data-target="#editModal"
                                                                            data-editAttendanceStatus="{{$datum->status}}"
                                                                            data-editAttendanceRemarks="{{$datum->remarks}}"
                                                                            data-date="{{$datum->attendance_date}}"
                                                                            data-editForm-url="{{route('admin.manager-smi.past-attendance.update',$datum->msmi_attendance_code)}}"
                                                                            class="btn btn-primary btn-xs">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </button>

                                                                    <button  id="remarkButton" data-toggle="modal" data-target="#remarkModal"
                                                                            data-remarks="{{$datum->remarks}}"
                                                                            class="btn btn-info btn-xs">
                                                                        <i class="fa fa-eye"></i> Remarks
                                                                    </button>

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
        $('document').ready(function(){
            $('#edit').click(function (e){
                e.preventDefault();
                let status = $(this).attr('data-editAttendanceStatus');
                let remarks = $(this).attr('data-editAttendanceRemarks');
                let date = $(this).attr('data-date');
                let url = $(this).attr('data-editForm-url');

                $('#pastAttendanceEditStatus').val(status);
                $('#pastAttendacneEditRemarks').text(remarks);
                $('h4.edit').text('Update Attendance Details '+date);
                $('#editAttendanceDetail').attr('action', url);
            })

            $('#remarkButton').click(function(e){
                e.preventDefault();
              var remarks = $(this).attr('data-remarks');
              $('p.remark').text(remarks);
            })

        });
    </script>
@endpush

