@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> 'Firm '.formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'firms'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')

            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.stores-kyc.firms')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="store_name">Store Name</label>
                                    <input type="text" class="form-control" name="store_name" id="store_name" value="{{$filterParameters['store_name']}}">
                                </div>


                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="business_registered_from">Business Registered From</label>
                                        <select name="business_registered_from" class="form-control" id="business_registered_from">
                                            <option value="" {{$filterParameters['business_registered_from'] == ''}}>All</option>
                                            @foreach($businessRegistrationTypes as $key=>$businessRegistrationType)
                                                <option value="{{$businessRegistrationType}}"
                                                        {{$businessRegistrationType == $filterParameters['business_registered_from'] ?'selected' :''}}>
                                                    {{ucwords($key)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="verification_status">Verification Status</label>
                                        <select name="verification_status" class="form-control" id="verification_status">
                                            <option value="" {{$filterParameters['verification_status'] == ''}}>All</option>
                                            @foreach($verificationStatuses as $verificationStatus)
                                                <option value="{{$verificationStatus}}"
                                                        {{$verificationStatus == $filterParameters['verification_status'] ?'selected' :''}}>
                                                    {{ucwords($verificationStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="submit_date_from">Submit Date From</label>
                                        <input type="date" class="form-control" name="submit_date_from" id="submit_date_from" value="{{$filterParameters['submit_date_from']}}">
                                    </div>

                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="submit_date_to">Submit Date To</label>
                                        <input type="date" class="form-control" name="submit_date_to" id="submit_date_to" value="{{$filterParameters['submit_date_to']}}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>


                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                               {{-- <a href="{{ route('admin.stores.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{$title}}
                                </a>--}}


                            </div>



                        </div>


                        <div class="box-body">
                            <table id="{{ $base_route }}-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
                                    <th>Business Registered From</th>
                                    <th>Submitted By</th>
                                    <th>Submitted At</th>
                                    <th>Verify Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($firmsKyc as $i => $firmKyc)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$firmKyc->store->store_name}}-{{($firmKyc->store_code)}}</td>
                                        <td>{{$firmKyc->business_registered_from}}</td>
                                        <td>{{$firmKyc->submittedBy->name}}</td>
                                        <td>
                                            {{date('M j Y', strtotime($firmKyc->created_at))}}
                                        </td>
                                        <td>
                                            @if($firmKyc->isVerified())
                                                <span class="label label-success">Verified</span>

                                                @if( $firmKyc['can_update_kyc'] == 1 )
                                                     <span style="margin-left:20px!important" class="label label-info">
                                                     <strong> Kyc Update Request Allowed : {{getNepTimeZoneDateTime($firmKyc['update_request_allowed_at'])}}</strong>
                                                     </span>
 
                                                 @endif
                                            @elseif($firmKyc->isRejected())
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif

                                        </td>


                                        <td>
                                            @canany(['Show Store Firm Kyc','Verify Store Firm Kyc'])
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores-kyc.firms.show', $firmKyc->kyc_code),'View Kyc', 'eye','primary')!!}
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
                            {{$firmsKyc->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection