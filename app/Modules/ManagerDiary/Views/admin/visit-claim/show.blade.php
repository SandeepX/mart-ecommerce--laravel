@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        #mapCanvas {
            min-width: 100%;
            min-height: 450px;
        }
    </style>
    <div class="content-wrapper">

    @include('Admin::layout.partials.flash_message')

    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=>"Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])
    <!-- Main content -->
        <section class="content">

            <div class="row">
                <div id="showFlashMessage"></div>
                <br>
                <!-- left column -->
                <div class="col-md-12">

                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{$title}} | Show</h3>
                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                               <a href="{{ route('admin.store-visit-claim-requests.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                               </a>
                               @if($storeVisitClaimRequest->status =='pending')
                                    <a data-href="{{route('admin.store-visit-claim-requests.respond.form',$storeVisitClaimRequest->store_visit_claim_request_code)}}" style="border-radius: 0px;" class="btn btn-sm btn-primary" id="request-respond" data-target="#visitClaimRespondModal">Respond</a>
                               @endif
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="card card-default">
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                @php
                                                    $status = [
                                                       'drafted' => 'default',
                                                       'pending' => 'warning',
                                                       'verified' => 'success',
                                                       'rejected' => 'danger'
                                                    ]
                                                @endphp

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Store Visit Claim Request Code</label>
                                                        <p>{{$storeVisitClaimRequest->store_visit_claim_request_code}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Diary Code</label>
                                                        <p>{{$storeVisitClaimRequest->manager_diary_code}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Store Name</label>
                                                        <p>
                                                            {{isset($storeVisitClaimRequest->managerDiary) ? $storeVisitClaimRequest->managerDiary->store_name : 'N/A'}}
                                                            -
                                                            {{  isset($storeVisitClaimRequest->managerDiary->referred_store_code) ? $storeVisitClaimRequest->managerDiary->referredStore->store_name.' ('.$storeVisitClaimRequest->managerDiary->referred_store_code.')' : 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>



                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Latitude</label>
                                                        <p>{{$storeVisitClaimRequest->manager_latitude}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Longitude</label>
                                                        <p>{{$storeVisitClaimRequest->manager_longitude}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Device</label>
                                                        <p>
                                                        @if(!empty(json_decode($storeVisitClaimRequest->manager_device_info)))
                                                            @foreach(json_decode($storeVisitClaimRequest->manager_device_info,true) as $key => $value)
                                                                <li style="margin-left: 15px">
                                                                    {{$key}} : @if($value === true)
                                                                                   True
                                                                                @elseif($value===false)
                                                                                    False
                                                                                @else
                                                                                  {{$value}}
                                                                                @endif
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            N/A
                                                        @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Store Latitude</label>
                                                        <p>{{isset($storeVisitClaimRequest->store_latitude) ? $storeVisitClaimRequest->store_latitude : 'N/A'}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Store Longitude</label>
                                                        <p>{{isset($storeVisitClaimRequest->store_longitude) ? $storeVisitClaimRequest->store_longitude : 'N/A'}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Store Device</label>
                                                        <p>
                                                            @if(!empty(json_decode($storeVisitClaimRequest->store_device_info)))
                                                                @foreach(json_decode($storeVisitClaimRequest->store_device_info,true) as $key => $value)
                                                                <li style="margin-left: 15px">
                                                                    {{$key}} : @if($value === true)
                                                                        True
                                                                    @elseif($value===false)
                                                                        False
                                                                    @else
                                                                        {{$value}}
                                                                    @endif
                                                                </li>
                                                                @endforeach
                                                            @else
                                                                    N/A
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Qr Scanned At</label>
                                                        <p>{{isset($storeVisitClaimRequest->qr_scanned_at) ? getReadableDate(getNepTimeZoneDateTime($storeVisitClaimRequest->qr_scanned_at)) : 'N/A'}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Status</label>
                                                        <p><span class="label label-{{$status[$storeVisitClaimRequest->status]}}">{{ucfirst($storeVisitClaimRequest->status)}}</span></p>
                                                    </div>
                                                </div>

                                                @if($storeVisitClaimRequest->responded_at)
                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Responded By</label>
                                                            <p>{{isset($storeVisitClaimRequest->respondedBy) ? $storeVisitClaimRequest->respondedBy->name : 'N/A'}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Responded At</label>
                                                            <p>{{isset($storeVisitClaimRequest->responded_at) ? getReadableDate(getNepTimeZoneDateTime($storeVisitClaimRequest->responded_at)) : 'N/A'}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Remarks</label>
                                                            <p>{!! $storeVisitClaimRequest->remarks !!}</p>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Submitted At</label>
                                                        <p>{{isset($storeVisitClaimRequest->submitted_at) ? getReadableDate(getNepTimeZoneDateTime($storeVisitClaimRequest->submitted_at)) : 'N/A'}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Visit Image</label>
                                                        <p>
                                                            @if($storeVisitClaimRequest->visit_image)
                                                             <a target="_blank"
                                                                href="{{photoToUrl($storeVisitClaimRequest->visit_image,url($storeVisitClaimRequest->getVisitImagePath()))}}">
                                                                 <img width="50" height="50" style="object-fit: cover"
                                                                      src="{{photoToUrl($storeVisitClaimRequest->visit_image,url($storeVisitClaimRequest->getVisitImagePath()))}}">
                                                             </a>
                                                            @else
                                                              N/A
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Created At</label>
                                                        <p>{{getReadableDate(getNepTimeZoneDateTime($storeVisitClaimRequest->created_at))}}</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="mapCanvas"></div>
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

    <div class="modal fade" id="visitClaimRespondModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection
@push('scripts')
<script>
    var closeButton =
        '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';

    function displayErrorMessage(data,flashElementId='showFlashMessage') {
        flashElementId='#'+flashElementId;
        var flashMessage = $(flashElementId);
        flashMessage. removeClass().addClass('alert alert-danger').show().empty();

        if (data.status == 422) {
            var errorString = "<ol type='1'>";
            for (error in data.responseJSON.data) {
                errorString += "<li>" + data.responseJSON.data[error] + "</li>";
            }
            errorString += "</ol>";
            flashMessage.html(closeButton + errorString);
        }
        else{
            flashMessage.html(closeButton + data.responseJSON.message);
        }
    }

    $('#request-respond').click(function(e){
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: $(this).attr('data-href')
        }).done(function(response) {
            $('#visitClaimRespondModal').modal('show');
            $('#visitClaimRespondModal .modal-content').empty().html(response);
        }).fail(function (data) {
            $('#visitClaimRespondModal').modal('hide');
            displayErrorMessage(data, 'showFlashMessage');
            scroll(0,0);
            $("#showFlashMessage").fadeOut(10000);
        });
    });
</script>

@includeIf('ManagerDiary::admin.visit-claim.scripts.map-script');
@endpush
