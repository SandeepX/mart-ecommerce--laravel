@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'listings'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')

            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.stores-kyc.listings')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="store_name">Store Name</label>
                                    <input type="text" class="form-control" name="store_name" id="store_name" value="{{$filterParameters['store_name']}}">
                                </div>


                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="kyc_for">Kyc For</label>
                                        <select name="kyc_for[]" class="form-control select2" id="kyc_for" multiple>
                                            @foreach($kycTypes as $kycType)
                                                <option value="{{$kycType}}"
                                                    @if($filterParameters['kyc_for']){{in_array($kycType, $filterParameters['kyc_for']) ?'selected' :''}} @endif>
                                                    {{ucwords($kycType)}}
                                                </option>
                                            @endforeach
                                            <option value="firm" @if($filterParameters['kyc_for']){{in_array('firm', $filterParameters['kyc_for']) ?'selected' :''}} @endif>Firm</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="kyc_for">Verification Status</label>
                                        <select name="verification_status" class="form-control " id="verification_status">
                                            <option value="" {{$filterParameters['verification_status'] == ''}}>All</option>
                                            @foreach($verification_status as $status)
                                                <option value="{{$status}}"
                                                @if($filterParameters['verification_status']){{$status ==$filterParameters['verification_status'] ?'selected' :''}} @endif>
                                                    {{ucwords($status)}}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>


                            <div class="col-xs-12">
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                </div>
                                <div class="col-xs-3">
                                <a href="{{route('admin.stores-kyc.listings')}}" class="btn btn-block btn-danger form-control">Clear</a>
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
                                List of {{  formatWords($title,true)}}
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">#</th>
                                    <th rowspan="2">Store</th>
                                    <th colspan="3">Kyc For</th>
                                </tr>
                                <tr>
                                    <th>Akhtiyari</th>
                                    <th>Sanchalak</th>
                                    <th>Firm</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($kyclistings as $i => $kyclisting)

                                <tr>
                                    <td><strong>{{++$i}}</strong></td>
                                    <td>
                                        <strong>
                                            {{ucwords($kyclisting->store_name)}}-{{($kyclisting->store_code)}}
                                        </strong>
                                    </td>
                                    @if($kyclisting->akhtiyari_latest_id)
                                        <td>
                                            <strong> -Last Updated At: {{getNepTimeZoneDateTime($kyclisting->akhtiyari_latest_updated_at)}} <br/></strong>
                                            <strong> -Last Verification Status: <span class="label @if($kyclisting->akhtiyari_last_verification_status =='pending') label-warning @elseif($kyclisting->akhtiyari_last_verification_status =='verified') label-success @else label-danger @endif">{{ucwords($kyclisting->akhtiyari_last_verification_status)}}</span> <br/></strong>
                                            @canany(['Show Store Individual Kyc','Verify Store Individual Kyc'])
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores-kyc.individuals.show', $kyclisting->akhtiyari_kyc_code),'View Kyc', 'eye','primary')!!}
                                            @endcanany
                                        </td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    @if($kyclisting->sanchalak_latest_id)
                                        <td>
                                            <strong> -Last Updated At: {{getNepTimeZoneDateTime($kyclisting->sanchalak_latest_updated_at)}} <br/></strong>
                                            <strong> -Last Verification Status: <span class="label @if($kyclisting->sanchalak_last_verification_status =='pending') label-warning @elseif($kyclisting->sanchalak_last_verification_status =='verified') label-success @else label-danger @endif">{{ucwords($kyclisting->sanchalak_last_verification_status)}}</span><br/></strong>
                                            @canany(['Show Store Individual Kyc','Verify Store Individual Kyc'])
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores-kyc.individuals.show', $kyclisting->sanchalak_kyc_code),'View Kyc', 'eye','primary')!!}
                                            @endcanany
                                        </td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    @if($kyclisting->firm_latest_id)
                                        <td>
                                            <strong> -Last Updated At: {{getNepTimeZoneDateTime($kyclisting->firm_latest_updated_at)}} <br/></strong>
                                            <strong> -Last Verification Status: <span class="label @if($kyclisting->firm_last_verification_status =='pending') label-warning @elseif($kyclisting->firm_last_verification_status =='verified') label-success @else label-danger @endif">{{ucwords($kyclisting->firm_last_verification_status)}}</span><br/></strong>
                                            @canany(['Show Store Individual Kyc','Verify Store Individual Kyc'])
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores-kyc.firms.show', $kyclisting->firm_kyc_code),'View Kyc', 'eye','primary')!!}
                                            @endcanany
                                        </td>
                                    @else
                                        <td>-</td>
                                    @endif

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
                            {{$kyclistings->appends($_GET)->links()}}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
