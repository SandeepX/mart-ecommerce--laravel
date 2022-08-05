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
                                <strong>
                                    FILTER STORE VISIT CLAIM REQUEST
                                </strong>
                            </div>

                            <div>
                                <div class="panel-body">
                                    <form action="{{route('admin.store-visit-claim-requests.index')}}" method="get">
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
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control select2" name="status" id="status">
                                                                <option value="">Please select</option>
                                                                <option value="drafted" {{ (isset($filterParameters) && $filterParameters['status']=='drafted') ? 'selected'  : ''}}>Drafted</option>
                                                                <option value="pending" {{ (isset($filterParameters) && $filterParameters['status']=='pending') ? 'selected'  : ''}}>Pending</option>
                                                                <option value="rejected" {{ (isset($filterParameters) && $filterParameters['status']=='rejected') ? 'selected'  : ''}}>Rejected</option>
                                                                <option value="verified" {{ (isset($filterParameters) && $filterParameters['status']=='verified') ? 'selected'  : ''}}>Verified</option>
                                                            </select>
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
                                List of Store Visit Claims Requests
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Visit Claim Code</th>
                                    <th>Manager Diary</th>
                                    <th>Store Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                $status = [
                                   'drafted' => 'default',
                                   'pending' => 'warning',
                                   'verified' => 'success',
                                   'rejected' => 'danger'
                                ]
                                @endphp
                                @forelse($storeVisitClaimRequests as $i => $storeVisitClaim)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$storeVisitClaim->store_visit_claim_request_code}}</td>
                                        <td><a target="_blank" href="{{route('admin.manager-diaries.detail', $storeVisitClaim->manager_diary_code)}}">{{$storeVisitClaim->manager_diary_code}}</a></td>
                                        <td>{{isset($storeVisitClaim->managerDiary) ? $storeVisitClaim->managerDiary->store_name : 'N/A'}} - {{  isset($storeVisitClaim->managerDiary->referred_store_code) ? $storeVisitClaim->managerDiary->referredStore->store_name.' ('.$storeVisitClaim->managerDiary->referred_store_code.')' : 'N/A' }}</td>
                                        <td><span class="label label-{{$status[$storeVisitClaim->status]}}">{{ucfirst($storeVisitClaim->status)}}</span></td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($storeVisitClaim->created_at))}}</td>
                                        <td>{{$storeVisitClaim->createdBy->name}}</td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.store-visit-claim-requests.show', $storeVisitClaim->store_visit_claim_request_code),'View Visit Claim Detail', 'eye','primary')!!}
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
                            {{$storeVisitClaimRequests->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

