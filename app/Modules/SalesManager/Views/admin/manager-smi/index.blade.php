
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
                width: 50px;
                height: 25px;
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
                height: 17px;
                width: 16px;
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
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-body">

                            <div class="col-xs-12">
                                <div class="panel-group">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong>
                                                Filter Social Media Influencer
                                            </strong>

                                            <div class="btn-group pull-right" role="group" aria-label="...">
                                                <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm" aria-expanded="true">
                                                    <strong>Filter</strong> <i class="fa fa-filter"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="collapse in" id="filter" aria-expanded="true" style="">
                                                    <div class="panel-body">
                                                        <form action="{{route('admin.manager-smi.index')}}" method="get">
                                                            @csrf
                                                            <div class="col-xs-3">
                                                                <div class="form-group">
                                                                    <label for="payment_code">Manager Name</label>
                                                                    <input type="text" class="form-control" placeholder="Manager Name" name="name" id="name" value="{{($filterParameters['name'])}}">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-3">
                                                                <div class="form-group">
                                                                    <label for="payment_code">Phone No.</label>
                                                                    <input type="text" class="form-control" placeholder="Phone No" name="phoneNumber" id="phoneNo" value="{{($filterParameters['manager_phone_no'])}}">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-3">
                                                                <div class="form-group">
                                                                    <label for="payment_type">Status</label>
                                                                    <select name="status" class="form-control select2" id="status">
                                                                        <option value="" {{$filterParameters['status']=='' ? 'selected':'' }}>All</option>
                                                                       @foreach($status as $key =>$value)
                                                                           <option value="{{$value}}" {{(isset($filterParameters['status']) && ($filterParameters['status'] == $value)? 'selected':'')}} >{{ucfirst($value)}}</option>
                                                                       @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-3">
                                                                <div class="form-group">
                                                                    <label for="from">Date From</label>
                                                                    <input type="date" class="form-control" name="from_date" id="fromDate" value="{{($filterParameters['from_date'])}}">
                                                                </div>

                                                            </div>

                                                            <div class="col-xs-3">
                                                                <div class="form-group">
                                                                    <label for="to"> Date To</label>
                                                                    <input type="date" class="form-control" name="to_date" id="toDate" value="{{($filterParameters['to_date'])}}">
                                                                </div>
                                                            </div>

{{--                                                            <div class="col-xs-3">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label for="payment_code">Province</label>--}}
{{--                                                                    <input type="text" class="form-control" placeholder="Province No" name="province" id="province" value="">--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}

{{--                                                            <div class="col-xs-3">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label for="payment_code">District</label>--}}
{{--                                                                    <input type="text" class="form-control" placeholder="District" name="district" id="district" value="">--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}

{{--                                                            <div class="col-xs-3">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label for="payment_code">Municipality</label>--}}
{{--                                                                    <input type="text" class="form-control" placeholder="Municipality" name="municipality" id="municipality" value="">--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}



                                                            <button type="submit" id="submit" class="btn btn-block btn-primary form-control">Search</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <strong>
                                                List Of Social Media Influencer
                                            </strong>
                                        </div>

                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="box-body">
                                                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Manager Name</th>
                                                            <th>Phone No.</th>
                                                            <th>Joined Date</th>
                                                            <th>Status</th>
                                                            <th>Is Active</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $status = [
                                                                'pending'=>'warning',
                                                                'approved'=>'success',
                                                                'rejected'=>'danger',
                                                            ]
                                                        ?>
                                                        @forelse($managerSMI as $key => $datum)
                                                            <tr>
                                                            <td>{{++$key}}</td>
                                                            <td>{{$datum->manager->manager_name}}</td>
                                                            <td>{{$datum->manager->manager_phone_no}}</td>
                                                            <td> {{ date('d-M-Y',strtotime($datum['created_at']))}}</td>
                                                            <td><span class="label label-{{$status[$datum->status]}}"> {{ucfirst($datum->status)}}</td>
                                                            <td>
                                                                @can('Change Manager SMI Status')
                                                                    <label class="switch">
                                                                        <input class="toggleStatus" href="{{route('admin.manager-smi.toggle-status',$datum->msmi_code)}}"  type="checkbox" {{($datum->is_active) === 1 ?'checked':''}}>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                @endcan
                                                            </td>

                                                            <td>

                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a href="{{route('admin.manager-smi.show',$datum->msmi_code)}}" target="">
                                                                                <button data-placement="left" data-tooltip="true" title="Detail" class="btn btn-xs btn-info">
                                                                                    <span class="fa fa-eye"></span>
                                                                                    Details
                                                                                </button>
                                                                            </a>

                                                                        </li>

                                                                        <li>
                                                                            <a href="{{route('admin.manager-smi.attendance.show',$datum->msmi_code)}}" target="">
                                                                                <button data-placement="left" data-tooltip="true" title="Documents" class="btn btn-xs btn-info">
                                                                                    <span class="fa fa-eye"></span>
                                                                                    Attendance
                                                                                </button>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>

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
                                                    {{$managerSMI->appends($_GET)->links()}}
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
        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to Change Status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }else if (result.isDenied) {
                    if (status === 0) {
                        $(this).prop('checked', true);
                    } else if (status === 1) {
                        $(this).prop('checked', false);
                    }
                }
            })
        })
    </script>
@endpush
















































{
