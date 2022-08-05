@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),

   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <br>
            @php
                $status = ['pending'=>'warning','accepted'=>'success','rejected'=>'danger','verified' => 'success'];
            @endphp

            <div class="col-xs-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Investment Plan Subscription Details
                        </h3>
                        <div class="pull-right" style="margin-top: -2px;margin-left: 10px;">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".bd-example-modal-sm">Investment Return At Maturity</button>
                        </div>

                        @can('Respond Investment Plan Subscription')
                            @if($subscribedIP->admin_status !== 'accepted')
                                <div class="pull-right" style="margin-top: -2px;margin-left: 10px;">
                                    <a data-href="{{route('admin.investment-subscription.respondIS.form',$subscribedIP->ip_subscription_code)}}" id="respondBtn" class="btn btn-primary " data-toggle="modal" data-target="#subscriptionRespondModal">Respond</a>
                                </div>
                            @endif
                        @endcan
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Investment Name</label>
                                    <p>{{$subscribedIP->investment_plan_name}}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Investment Type</label>
                                    <p>{{ucfirst($subscribedIP->investmentPlan->investmentType->name)}}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Investment Plan Holder</label>
                                    <p> {{ucfirst($subscribedIP->investment_holder_type)}}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Investment Holder Id</label>
                                    <p> {{$subscribedIP->subscription_holder_name}} ({{$subscribedIP->investment_holder_id}})</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Maturity Period</label>
                                    <p> {{$subscribedIP->maturity_period}}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Mature Date</label>
                                    <p>{{$subscribedIP->maturity_date}}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Interest Rate</label>
                                    <p> {{$subscribedIP->interest_rate}} %</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Admin Status</label>
                                    <p>
                                        <span class="label label-{{$status[$subscribedIP->admin_status]}}">
                                           {{ucfirst($subscribedIP->admin_status)}}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <p>
                                        <span class="label label-{{$status[$subscribedIP->status]}}">
                                           {{ucfirst($subscribedIP->status)}}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Payment Mode</label>
                                    <p>{{ucwords($subscribedIP->payment_mode)}}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Referred By</label>
                                    <p>{{isset($subscribedIP->referredBy) ? $subscribedIP->referredBy->manager_name.' ('.$subscribedIP->referred_by.')' : 'N/A'}}</p>
                                </div>
                            </div>
                            @if($subscribedIP->admin_status !== 'pending')
                                <div class="col-md-3 col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Last Updated At</label>
                                        <p>{{getReadableDate(getNepTimeZoneDateTime($subscribedIP->updated_at),'Y-M-d')}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Updated By</label>
                                        <p>{{$subscribedIP->updatedBy->name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Created By</label>
                                        <p>{{$subscribedIP->createdBy->name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label">Remarks</label>
                                        <p>{!! $subscribedIP->admin_remark !!}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Is Active</label>
                                    <p>
                                        @if($subscribedIP->is_active == 1)
                                            <span style='font-size:25px;'>&#10004;</span>
                                        @elseif($subscribedIP->is_active == 0)
                                            <span style='font-size:25px;'>&#10006;</span>
                                        @endif
                                    </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            @if($subscribedIP->payment_mode == 'online')
                @include('InvestmentPlan::Investment-plan-subscription.admin.partials.online-payment')
            @else
                @include('InvestmentPlan::Investment-plan-subscription.admin.partials.offline-payment')
            @endif

            <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="box-body">
                            <h4><b>Investment Return At Maturity</b></h4>
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <tbody>
                                <tr>
                                    <td>Invested Amount</td>
                                    <td>Rs.{{$subscribedIP->invested_amount}}</td>
                                </tr>
                                <tr>
                                    <td>Principle</td>
                                    <td>Rs.{{ isset($investmentReturn['principle']) ? $investmentReturn['principle'] : 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Interest</td>
                                    <td>Rs. {{ isset($investmentReturn['interest']) ? $investmentReturn['interest'] : 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Share</td>
                                    <td>{{ isset($investmentReturn['share']) ? $investmentReturn['share'] : 0  }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="subscriptionRespondModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
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

        $('#respondBtn').click(function(e){
            e.preventDefault();
            $.ajax({
                type: 'GET',
                url: $(this).attr('data-href')
            }).done(function(response) {
                $('#subscriptionRespondModal').modal('show');
                $('#subscriptionRespondModal .modal-content').empty().html(response);
            }).fail(function (data) {
                $('#subscriptionRespondModal').modal('hide');
                displayErrorMessage(data, 'showFlashMessage');
                scroll(0,0);
                $("#showFlashMessage").fadeOut(10000);
            });
        });

    </script>
@endpush

