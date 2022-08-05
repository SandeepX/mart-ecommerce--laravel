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
    'manage_url'=>route($base_route.'.index',$manager->manager_code),
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
                                    FILTER MANAGER DIARIES
                                </strong>
                            </div>

                            <div>
                                <div class="panel-body">
                                    <form action="{{route('admin.manager-diaries.index',$manager->manager_code)}}" method="get">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Store Name</label>
                                                            <input type="text" name="store_name" id="store_name" class="form-control" value="{{$filterParameters['store_name']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Is Referred</label>
                                                            <select name="is_referred" class="form-control select2" >
                                                                <option value="">All</option>
                                                                <option value="yes" {{ (isset($filterParameters) && $filterParameters['is_referred']=='yes') ? 'selected'  : ''}}>Yes</option>
                                                                <option value="no" {{ (isset($filterParameters) && $filterParameters['is_referred']=='no') ? 'selected'  : ''}}>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Owner Name</label>
                                                            <input type="text" name="owner_name" id="owner_name" class="form-control" value="{{$filterParameters['owner_name']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Phone No</label>
                                                            <input type="text" name="phone_no" id="phone_no" class="form-control" value="{{$filterParameters['phone_no']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="amount_condition">Amount Condition</label>
                                                            <select name="amount_condition" class="form-control select2" id="amount_condition">
                                                                <option value="" {{$filterParameters['amount_condition'] == ''}}>All</option>
                                                                @foreach($priceConditions as $key=>$priceCondition)
                                                                    <option value="{{$priceCondition}}"
                                                                        {{$priceCondition == $filterParameters['amount_condition'] ?'selected' :''}}>
                                                                        {{ucwords($key)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Amount</label>
                                                            <input type="text" name="amount" id="amount" class="form-control" value="{{$filterParameters['amount']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Date From</label>
                                                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{$filterParameters['date_from']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Date To</label>
                                                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{$filterParameters['date_to']}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong>LOCATION</strong>
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <div class="btn-group" role="group">
                                                        <button data-toggle="collapse" data-target="#location" type="button" class="btn btn-sm">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div  class="col-xs-12">
                                                    <div id="location" class="collapse">
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="province_code" class="control-label">Province</label>
                                                                <select class="form-control" id="province_code" name="province_code" >
                                                                    <option selected value="" >--Select An Option--</option>
                                                                    @if(isset($provinces) && count($provinces)>0)
                                                                        @foreach ($provinces as $province)
                                                                            <option value={{ $province->location_code }} {{ $filterParameters['province_code'] == $province->location_code ? 'selected': '' }}>{{ $province->location_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="district_code" class="control-label">District</label>
                                                                <select name="district_code" class="form-control" id="district_code" onchange="districtChange()">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="municipality_code" class="control-label">Municipality</label>
                                                                <select name="municipality_code" class="form-control" id="municipality_code" onchange="municipalityChange()">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-3">
                                                            <div class="form-group">
                                                                <label for="ward_code" class="control-label">Ward</label>
                                                                <select name="ward_code" class="form-control" id="ward_code">
                                                                    <option selected value="" >--Select An Option--</option>
                                                                </select>
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
                                List of {{formatWords($title,true)}} of {{$manager->manager_name}} ({{$manager->manager_code}})
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Diary Code</th>
                                    <th>Store Name</th>
                                    <th>References Store</th>
                                    <th>Owner Name</th>
                                    <th>Phone No</th>
                                    <th>Location</th>
                                    <th>Investment Amount</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($managerDiaries as $i => $managerDiary)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$managerDiary->manager_diary_code}}</td>
                                        <td>{{$managerDiary->store_name}}</td>
                                        <td>{{  isset($managerDiary->referred_store_code) ? $managerDiary->referredStore->store_name.' ('.$managerDiary->referred_store_code.')' : 'N/A' }}</td>
                                        <td>{{$managerDiary->owner_name}}</td>
                                        <td>{{$managerDiary->phone_no}}</td>
                                        <td>{{$managerDiary->full_location}}</td>
                                        <td>{{getNumberFormattedAmount($managerDiary->business_investment_amount)}}</td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($managerDiary->created_at))}}</td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.manager-diaries.detail', $managerDiary->manager_diary_code),'view ManagerDiary Detail', 'eye','primary')!!}
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
                            {{$managerDiaries->appends($_GET)->links()}}
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
    @includeIf('ManagerDiary::admin.manager-diary.scripts.diary-scripts');
    <script>
        $(document).ready(function(){
            let province = '{{isset($filterParameters['province_code'])}}';
            if(province){
              $('#location').addClass('in')
            }
        });
    </script>
@endpush
