@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'individuals'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.stores-kyc.individuals')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="store_name">Store Name</label>
                                    <input type="text" class="form-control" name="store_name" id="store_name" value="{{$filterParameters['store_name']}}">
                                </div>


                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="kyc_for">Kyc For</label>
                                        <select name="kyc_for" class="form-control" id="kyc_for">
                                            <option value="" {{$filterParameters['kyc_for'] == ''}}>All</option>
                                            @foreach($kycTypes as $kycType)
                                                <option value="{{$kycType}}"
                                                        {{$kycType == $filterParameters['kyc_for'] ?'selected' :''}}>
                                                    {{ucwords($kycType)}}
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
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
                                    <th>Kyc For</th>
                                    <th>Submitted At</th>
                                    <th>Verify Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($individualsKyc as $i => $individualKyc)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$individualKyc->store->store_name}}-{{($individualKyc->store_code)}}</td>
                                        <td>{{$individualKyc->kyc_for}}</td>
                                        <td>
                                            {{date('M j Y', strtotime($individualKyc->created_at))}}
                                        </td>
                                        <td>
                                            @if($individualKyc->isVerified())
                                                <span class="label label-success">Verified</span>
                                                
                                                @if( $individualKyc['can_update_kyc'] == 1 )
                                                     <span style="margin-left:20px!important" class="label label-info">
                                                     <strong> Kyc Update Request Allowed : {{getNepTimeZoneDateTime($individualKyc['update_request_allowed_at'])}}</strong>
                                                     </span>
 
                                                 @endif
                                            @elseif($individualKyc->isRejected())
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif

                                        </td>


                                        <td>
                                            @canany(['Show Store Individual Kyc','Verify Store Individual Kyc'])
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores-kyc.individuals.show', $individualKyc->kyc_code),'View Kyc', 'eye','primary')!!}
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

                            {{$individualsKyc->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection