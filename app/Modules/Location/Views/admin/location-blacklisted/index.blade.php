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
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
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
                            <form action="{{ route('admin.location-blacklisted.index') }}" method="get">

                                <div class="col-xs-3">
                                    <label for="location_name">Location Name(ward)</label>
                                    <input type="text" class="form-control" placeholder="e.g 6"name="location_name" id="location_name" value="{{$filterParameters['location_name']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="from_date">Created From</label>
                                    <input type="date" class="form-control" name="from_date" id="from_date" value="{{$filterParameters['from_date']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="to_date">Created Upto</label>
                                    <input type="date" class="form-control" name="to_date" id="to_date" value="{{$filterParameters['to_date']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">
                                            All
                                        </option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm pull-right">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Blacklisted Location
                            </h3>

                            @can('Blacklist Location')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.location-blacklisted.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Blacklist New Location
                                    </a>
                                </div>
                            @endcan

                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
{{--                                    <th>Location Code</th>--}}
                                    <th>Location Name(Ward)</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($getAllBlackListedLocation as $key => $value)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        {{--                                  <td>{{$value->blacklisted_location_hierarchy_code}}</td>--}}
                                        <td>{{$value->location->upperLocation->location_name}} <b>{{$value->location->location_name}} </b>({{$value->location_code}})</td>
                                        <td>{{convertToWords($value->purpose)}}</td>
                                        <td>
                                            @can('Change Status Of Blacklisted Location')
                                                <label class="switch">
                                                    <input class="toggleStatus" href="{{route('admin.blacklisted-location.toggle-status',$value->blacklisted_location_hierarchy_code)}}" data-InvestmentCode="{{$value->blacklisted_location_hierarchy_code}}" type="checkbox" {{($value->status) === 1 ?'checked':''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            @endcan
                                        </td>
                                        <td>{{$value->createdBy->name}}</td>
                                        <td>{{date_format($value->created_at,"M d Y")}}</td>
                                        <td>
                                            @can('Update Blacklisted Location')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.location-blacklisted.edit',$value->blacklisted_location_hierarchy_code ),'Edit BlackListed Location', 'pencil','warning')!!}
                                            @endcan

                                            @can('Delete BlackList Location')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.location-blacklisted.destroy',$value->blacklisted_location_hierarchy_code ),$value,'Location BlackListed(ward) '.$value->location->location_name,'Blacklisted Location' )!!}
                                            @endcan
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
                            {{$getAllBlackListedLocation->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
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
                title: 'Are you sure you want to change BlackListed Location Status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
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
