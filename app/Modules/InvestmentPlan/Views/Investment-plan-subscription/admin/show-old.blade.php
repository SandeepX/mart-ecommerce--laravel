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
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="box box-success">
                                    <div class="box-header with-border">

                                        @php
                                            $status = ['pending'=>'warning','accepted'=>'success','rejected'=>'danger'];
                                        @endphp
                                        <span style="font-size: 17px;" class="label label-{{$status[$subscribedIP->admin_status]}}">
                                            Status: {{ucfirst($subscribedIP->admin_status)}}
                                        </span>

                                        <div class="pull-left" style="margin-top: -2px;margin-right: 5px;">
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".bd-example-modal-sm">Investment Return At Maturity</button>
                                        </div>
                                        @can('Respond Investment Plan Subscription')
                                        @if($subscribedIP->admin_status !== 'accepted')
                                            <div class="pull-right" style="margin-top: -2px;margin-left: 10px;">
                                                <a href="javascript:void(0)" id="respond_btn" style="border-radius: 5px; " class="btn btn-sm btn-primary">
                                                    <i class="fa fa-reply"></i>
                                                    Respond
                                                </a>
                                            </div>
                                        @endif
                                        @endcan
                                    </div>

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

                                    <div class="box-body">
                                        <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                            <tbody>
                                            <tr>
                                                <td>Investment Name</td>
                                                <td>{{$subscribedIP->investment_plan_name}}</td>
                                            </tr>

                                            <tr>
                                                <td>Investment Type</td>
                                                <td>{{ucfirst($subscribedIP->investmentPlan->investmentType->name)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Investment Plan Holder</td>
                                                <td>
                                                    {{$subscribedIP->investment_plan_holder}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Investment Holder Id</td>
                                                <td>
                                                    {{$subscribedIP->investment_holder_id}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Maturity Period</td>
                                                <td>
                                                    {{$subscribedIP->maturity_period}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Mature Date</td>
                                                <td>
                                                    {{$subscribedIP->maturity_date}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Interest Rate</td>
                                                <td>
                                                    {{$subscribedIP->interest_rate}} %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Is Active</td>
                                                <td>@if($subscribedIP->is_active == 1)
                                                        <span style='font-size:25px;'>&#10004;</span>
                                                    @elseif($subscribedIP->is_active == 0)
                                                        <span style='font-size:25px;'>&#10006;</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($subscribedIP->admin_status !== 'pending')
                                                <tr>
                                                    <td>Last Updated At</td>
                                                    <td>{{getReadableDate(getNepTimeZoneDateTime($subscribedIP->updated_at),'Y-M-d')}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Updated By</td>
                                                    <td>{{$subscribedIP->updatedBy->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Created By</td>
                                                    <td>{{$subscribedIP->createdBy->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Remarks</td>
                                                    <td>{!! $subscribedIP->admin_remark !!}</td>
                                                </tr>
                                            @endif
                                            </tbody>

                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                        @if($subscribedIP->admin_status !== 'accepted')
                            <div class="box-body" id="respond_form" {{old('status') ? '' :'hidden'}}>
                                <form class="form-horizontal" role="form" id="formVerification"
                                      action="{{route('admin.investment-subscription.respondIS',$subscribedIP->ip_subscription_code)}}"
                                      method="post">

                                    {{csrf_field()}}

                                    <div class="box-body">

                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="verification_status" class="control-label">Verification
                                                    Status</label>
                                                <select id="verification_status" name="admin_status"
                                                        class="form-control" required>
                                                    <option value="">select status</option>
                                                    <option value="accepted"
                                                        {{old('status') == 'accepted' ? 'selected' : ''}}>
                                                        Accept
                                                    </option>
                                                    <option value="rejected"
                                                        {{old('status') == 'rejected' ? 'selected' : ''}}>
                                                        Reject
                                                    </option>

                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="remarks" class="control-label">Remarks</label>
                                                <textarea id="remarks" class="form-control summernote" name="admin_remark"
                                                          placeholder="Enter remarks">{{old('remarks')}}</textarea>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 17%;" id="saveMiscPayment"
                                                class="btn btn-block btn-primary">Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include('InvestmentPlan::Investment-plan-subscription.admin.common.respond-form-script')
    <script>
        $('#verification_status').change(function (e){
            e.preventDefault();

            var brRadioSelects = document.getElementsByClassName("radio_select_br_code");

            var status = $(this).val();
            if(status === 'accepted'){
                for(var i=0; i<brRadioSelects.length; i++) {
                    brRadioSelects[i].required = true
                }
            }

            if(status === 'rejected'){
                for(var i=0; i<brRadioSelects.length; i++) {
                    brRadioSelects[i].required = false
                }
            }
        })

        $('#formVerification').submit(function (e, params) {
            var localParams = params || {};
            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to save the changes ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });

    </script>



@endpush
