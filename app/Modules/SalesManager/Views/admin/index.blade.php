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
                <div class="col-xs-12">

                    <div class="panel-group">
                        <div class="panel panel-success">

                            <div class="panel-heading">
                                <strong >
                                   FILTER SALES MANAGER
                                </strong>
                                {{--@if(isset($filterParameters['province']) || isset($filterParameters['temporary_province']))--}}
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                        <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter_location" type="button" class="btn btn-sm">
                                            <strong>Filter By Location</strong> <i class="fa fa-arrow-down"></i>
                                        </button>
                                </div>
{{--                              @endif--}}
                            </div>

                            <div>
                                <div class="panel-body">
                                    <form action="{{route('admin.salesmanager.index')}}" method="get">

                                    <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="user_name">Name</label>
                                                            <input name="name" id="name" class="form-control" value="{{$filterParameters['name']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="user_name">Status</label>
                                                            <select name="status" class="form-control select2" >
                                                                <option value="">All</option>
                                                                @foreach($status as $status)
                                                                    <option value="{{$status}}" {{$filterParameters['status'] == $status ? 'selected' : ''}}>{{ucwords($status)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4"></div>
                                                </div>
                                            </div>
                                    </div>

                                    <div style="margin-top: 10px" id="filter_location" class="collapse">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong>PERMANENT LOCATION</strong>
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <div class="btn-group" role="group">
                                                        <button data-toggle="collapse" data-target="#permanent_location" type="button" class="btn btn-sm">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div id="permanent_location"  class="collapse">
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="province" class="control-label">Province</label>
                                                                <select class="form-control" id="province" name="province" >
                                                                    <option selected value="" >--Select An Option--</option>
                                                                    @if(isset($provinces) && count($provinces)>0)
                                                                        @foreach ($provinces as $province)
                                                                            <option value={{ $province->location_code }} {{ $filterParameters['province'] == $province->location_code ? 'selected': '' }}>{{ $province->location_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="district" class="control-label">District</label>
                                                                <select name="district" class="form-control" id="district" onchange="districtChange()">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="municipality" class="control-label">Municipality</label>
                                                                <select name="municipality" class="form-control" id="municipality" onchange="municipalityChange()">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="ward" class="control-label">Ward</label>
                                                                <select name="ward" class="form-control" id="ward">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong>TEMPORARY LOCATION</strong>
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <div class="btn-group" role="group">
                                                        <button data-toggle="collapse" data-target="#temporary_location" type="button" class="btn btn-sm">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div  class="col-xs-12">
                                                    <div id="temporary_location" class="collapse">
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="temporary_province" class="control-label">Province</label>
                                                                <select class="form-control" id="temporary_province" name="temporary_province" >
                                                                    <option selected value="" >--Select An Option--</option>
                                                                    @if(isset($provinces) && count($provinces)>0)
                                                                        @foreach ($provinces as $province)
                                                                            <option value={{ $province->location_code }} {{ $filterParameters['temporary_province'] == $province->location_code ? 'selected': '' }}>{{ $province->location_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="temporary_district" class="control-label">District</label>
                                                                <select name="temporary_district" class="form-control" id="temporary_district" onchange="temporaryDistrictChange()">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="temporary_municipality" class="control-label">Municipality</label>
                                                                <select name="temporary_municipality" class="form-control" id="temporary_municipality" onchange="temporaryMunicipalityChange()">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="temporary_ward" class="control-label">Ward</label>
                                                                <select name="temporary_ward" class="form-control" id="temporary_ward">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <br>
                                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                                    </form>
                                </div>
                            </div>
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
{{--                                    <th>User Type</th>--}}
                                    <th>Name</th>
{{--                                    <th>See detail</th>--}}
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Active</th>
                                    <th>Responded At</th>
                                    <th>Status</th>
                                    <th>Referral Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($sales_managers as $i => $manager)

                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td>
                                            <a href="{{route('admin.salesmanager.show', $manager->manager_code)}}"><b>{{ucfirst($manager->manager_name)}}</b></a>
                                        </td>

                                        <td>{{$manager->manager_email}}</td>
                                        <td>{{$manager->manager_phone_no}}</td>
                                        <td>
                                           @if($manager->is_active==1)
                                                <span class="label label-success">Active</span>
                                            @else
                                               <span class="label label-danger">Inactive</span>
                                           @endif
                                        </td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($manager->status_responded_at),'Y-M-d')}}</td>
                                        <td>{{ucfirst($manager->status)}}</td>
                                        <td>{{isset($manager->referral_code) ? $manager->referral_code : 'N/A'}}</td>

                                        <td>

                                            @if($manager->status!='rejected')
                                            <!-- Button trigger modal -->
                                            @can('Assign Stores To Manager')
                                                <a href="{{route('admin.salesmanager.assignStore.create',$manager->manager_code)}}">
                                                    <button type="button" class="btn btn-primary btn-xs assignStore">Store Assignment</button>
                                                </a>
                                            @endcan
                                            @can('View All Referred Store')
                                                <a href="{{route('admin.salesmanager.referredStore.show',$manager->manager_code)}}">
                                                    <button type="button" class="btn btn-primary btn-xs assignStore">Referred Store</button>
                                                </a>
                                            @endcan

                                                <a href="{{route('admin.salesmanager.referredManager.show',$manager->manager_code)}}">
                                                    <button type="button" class="btn btn-primary btn-xs assignStore">Referred Manager</button>
                                                </a>
                                            @endif
                                            @can('Update Manager Password')
                                                <a href="{{route('admin.salesManager.changePassword.show',$manager->user_code)}}">
                                                    <button type="button" class="btn btn-primary btn-xs assignStore">Change Password</button>
                                                </a>
                                            @endcan

                                                <a href="{{route('admin.manager-diaries.index',$manager->manager_code)}}">
                                                    <button type="button" class="btn btn-primary btn-xs assignStore">Manager Diary</button>
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
                            {{$sales_managers->appends($_GET)->links()}}
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
    @includeIf('SalesManager::admin.scripts.permanent_location_script');
    @includeIf('SalesManager::admin.scripts.temporary_location_script');
    <script>
        $(document).ready(function(){
            let permanentFilter = '{{isset($filterParameters['province'])}}';
            let temporaryFilter = '{{isset($filterParameters['temporary_province'])}}';

            if(permanentFilter || temporaryFilter){
                $('#filter_location').addClass('in')
                if(permanentFilter){
                    $('#permanent_location').addClass('in')
                }
            if(temporaryFilter){
                $('#temporary_location').addClass('in')
            }
            }
        })
    </script>
@endpush
