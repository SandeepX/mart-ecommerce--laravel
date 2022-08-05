@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.location-hierarchies.index')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="location_name">Location Name</label>
                                    <input type="text" class="form-control" name="location_name" id="location_name" value="{{$filterParameters['location_name']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="location_type">Location Type</label>
                                    <select id="location_type" name="location_type" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($locationTypes as $locationType)
                                            <option value="{{$locationType}}"
                                                    {{$locationType== $filterParameters['location_type'] ?'selected' :''}}>
                                                {{ucwords($locationType)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="province" class="control-label">Province  *</label>
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
                                        <label for="district" class="control-label">District  *</label>
                                        <select name="district" class="form-control" id="district" onchange="districtChange()">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="municipality" class="control-label">Municipality  *</label>
                                        <select name="municipality" class="form-control" id="municipality" onchange="municipalityChange()">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>


                               {{-- <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="ward" class="control-label">Ward  *</label>
                                        <select class="form-control" id="ward"  name="ward">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>--}}

                                <button type="submit" class="btn btn-primary btn-sm pull-right">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">

                    
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of Location Hierarchies
                                </h3>

                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.location-hierarchies.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Location Hierarchy
                                    </a>
                                </div>

                            </div>

                            <div class="box-body">

                                {{--<div class="panel-body">--}}

                                    {{--<div class="col-md-12">--}}
                                        {{--<form method="GET" action="http://localhost:8001/accounting/load_ledgers_report" id="load-report-form" class="form-inline" role="form">--}}

                                            {{--<div class="form-group">--}}
                                                {{--<label for="name">Province</label>--}}

                                                {{--<select style="width: 200px;" name="ledger_id" class="form-control" id="ledger_id" required="">--}}
                                                    {{--<option value="1">Suppliers</option>--}}
                                                    {{--<option value="2">Discount Allowed A/C</option>--}}
                                                    {{--<option value="3">Prajwal</option>--}}
                                                {{--</select>--}}
                                            {{--</div>&nbsp;--}}


                                            {{--<div class="form-group">--}}
                                                {{--<label for="name">District</label>--}}

                                                {{--<select style="width: 200px;" name="ledger_id" class="form-control" id="ledger_id" required="">--}}
                                                    {{--<option value="1">Suppliers</option>--}}
                                                    {{--<option value="2">Discount Allowed A/C</option>--}}
                                                    {{--<option value="3">Prajwal</option>--}}
                                                {{--</select>--}}
                                            {{--</div>&nbsp;--}}

                                            {{--<div class="form-group">--}}
                                                {{--<label for="name">Municipality</label>--}}

                                                {{--<select style="width: 200px;" name="ledger_id" class="form-control" id="ledger_id" required="">--}}
                                                    {{--<option value="1">Suppliers</option>--}}
                                                    {{--<option value="2">Discount Allowed A/C</option>--}}
                                                    {{--<option value="3">Prajwal</option>--}}
                                                {{--</select>--}}
                                            {{--</div>&nbsp;--}}



                                            {{--<div style="margin-top: 10px;" class="form-group">--}}
                                                {{--<label for="name">Ward</label>--}}

                                                {{--<select style="width: 200px;" name="ledger_id" class="form-control" id="ledger_id" required="">--}}
                                                    {{--<option value="1">Suppliers</option>--}}
                                                    {{--<option value="2">Discount Allowed A/C</option>--}}
                                                    {{--<option value="3">Prajwal</option>--}}
                                                {{--</select>--}}
                                            {{--</div>&nbsp;--}}


                                            {{--<button type="submit" id="load_button" class="btn btn-primary"><i class="fa fa-check-circle-o"></i>&nbsp;Load</button>--}}

                                        {{--</form>--}}



                                    {{--</div>--}}

                                {{--</div>--}}

                                {{--<hr>--}}

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Devanagari Name</th>
                                        <!-- <th>Code</th> -->
                                        <th>Type</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($locationHierarchies as $i => $locationHierarchy)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$locationHierarchy->location_name}}</td>
                                            <td>{{$locationHierarchy->location_name_devanagari}}</td>
                                            <!-- <td>{{$locationHierarchy->location_code}}</td> -->
                                            <td>{{$locationHierarchy->location_type}}</td>
                                            <!-- <td>

                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.location-hierarchies.edit', $locationHierarchy->slug),'Edit Location', 'pencil','primary')!!}


                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.location-hierarchies.destroy',$locationHierarchy->location_code),$locationHierarchy,'Location',$locationHierarchy->location_name)!!}

                                            </td> -->
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

                                {{ $locationHierarchies->appends($_GET)->links() }}
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
@includeIf('Location::admin.filter-script');
@endpush