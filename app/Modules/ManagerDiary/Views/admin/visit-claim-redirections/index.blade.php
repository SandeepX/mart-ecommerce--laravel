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
                                    Filter {{formatWords($title,true)}}
                                </strong>
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
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route('admin.visit-claim-scan-redirection.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{formatWords($title,true)}}
                                </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>App Page</th>
                                    <th>External Link</th>
                                    <th>Is Active</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                $actives = [
                                        0=>'danger',
                                        1=>'success'
                                    ]
                                @endphp
                                @forelse($storeVisitClaimRedirections as $i => $storeVisitClaimRedirection)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$storeVisitClaimRedirection->title}}</td>
                                        <td><a target="_blank" href="{{asset($storeVisitClaimRedirection->getImageUploadPath().$storeVisitClaimRedirection->image)}}">{{$storeVisitClaimRedirection->image}}</a></td>
                                        <td>{{ isset($storeVisitClaimRedirection->app_page) ? $storeVisitClaimRedirection->app_page : 'N/A' }}</td>
                                        <td>
                                            @if(isset($storeVisitClaimRedirection->external_link))
                                                <a target="_blank" href="{{$storeVisitClaimRedirection->external_link}}">{{$storeVisitClaimRedirection->external_link}}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td><span class="label label-{{$actives[$storeVisitClaimRedirection->is_active]}}">{{($storeVisitClaimRedirection->is_active) ? 'Yes' : 'No'}}</span></td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit',route('admin.visit-claim-scan-redirection.edit', $storeVisitClaimRedirection->store_visit_claim_scan_redirection_code),'edit Scan redirection Detail', 'pencil','primary')!!}
                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.visit-claim-scan-redirection.destroy',$storeVisitClaimRedirection->store_visit_claim_scan_redirection_code),$storeVisitClaimRedirection,'Scan Redirection',$storeVisitClaimRedirection->store_visit_claim_scan_redirection_code)!!}
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
                            {{$storeVisitClaimRedirections->appends($_GET)->links()}}
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

@endpush
