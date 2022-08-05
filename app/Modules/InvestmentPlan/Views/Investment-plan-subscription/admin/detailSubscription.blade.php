@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            <style>
                .box-color {
                    float: left;
                    height: 15px;
                    width: 10px;
                    padding-top: 5px;
                    border: 1px solid black;
                }

                .danger-color {
                    background-color:  #ff667a ;
                }

                .warning-color {
                    background-color:  #f5c571 ;
                }

                .switch {
                    position: relative;
                    display: inline-block;
                    width: 50px;
                    height: 25px;
                }
                .switch input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                }
                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #F21805;
                    -webkit-transition: .4s;
                    transition: .4s;
                }
                .slider:before {
                    position: absolute;
                    content: "";
                    height: 17px;
                    width: 16px;
                    left: 4px;
                    bottom: 4px;
                    background-color: white;
                    -webkit-transition: .4s;
                    transition: .4s;
                }
                input:checked + .slider {
                    background-color: #50C443;
                }
                input:focus + .slider {
                    box-shadow: 0 0 1px #50C443;
                }
                input:checked + .slider:before {
                    -webkit-transform: translateX(26px);
                    -ms-transform: translateX(26px);
                    transform: translateX(26px);
                }
                /* Rounded sliders */
                .slider.round {
                    border-radius: 34px;
                }
                .slider.round:before {
                    border-radius: 50%;
                }
            </style>

            @include('Admin::layout.partials.flash_message')
            <div class="row">


                <div class="col-xs-12">

                    <div class="panel-group">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong >
                                    FILTER INVESTMENT PLAN SUBSCRIPTION : {{ (count($subscribedIP)>0) ? $subscribedIP[0]->investment_plan_name : '' }}
                                </strong>

                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm">
                                        <strong>Filter</strong> <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <div class="panel-body" >
                                    <div class="panel panel-default">
                                        <div class="collapse" id="filter">
                                            <div class="panel-body" >
                                                <form action="{{route('admin.investment-subscription.detail-show',$filterParameters['ip_code'])}}" method="get">

{{--                                                    <div class="col-xs-4">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <label for="">Investment Plan Name </label>--}}
{{--                                                            <input type="text" class="form-control" name="investment_plan_name" id="investment_plan_name"--}}
{{--                                                                   value="{{$filterParameters['investment_plan_name']}}">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                    <div class="col-xs-4">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <label for="">Investment Holder Type </label>--}}
{{--                                                            <input type="text" class="form-control" name="investment_holder_type" id="investment_holder_type"--}}
{{--                                                                   value="{{$filterParameters['investment_holder_type']}}">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

                                                    <div class = "col-xs-4">
                                                        <div class = "form-group">
                                                            <label for = "">Investment Holder Type</label>
                                                            <select name="investment_holder_type" class="form-control" id="investment_holder_type" >
                                                                <option value="">Select All </option>
                                                                <option value="user" {{  ($filterParameters['investment_holder_type'] == 'user')?'selected':''}}>User</option>
                                                                <option value="manager" {{  ($filterParameters['investment_holder_type'] == 'manager')?'selected':''}}>Manager</option>
                                                                <option value="vendor"  {{  ($filterParameters['investment_holder_type'] == 'vendor')?'selected':''}}>Vendor</option>
                                                                <option value="store"  {{  ($filterParameters['investment_holder_type'] == 'store')?'selected':''}}>Store</option>
                                                            </select>
                                                        </div>
                                                    </div>

{{--                                                    <div class="col-xs-4">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <label for="">Investment Holder Name</label>--}}
{{--                                                            <input type="text"  class="form-control" name="investment_holder_name" id="investment_holder_name"--}}
{{--                                                                   value="{{$filterParameters['investment_holder_name']}}">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}


                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Mature Date from</label>
                                                            <input type="date"  class="form-control" name="maturity_date_from" id="maturity_date_from"
                                                                   value="{{$filterParameters['maturity_date_from']}}" >
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Mature Date To</label>
                                                            <input type="date"  class="form-control" name="maturity_date_to" id="maturity_date_to"
                                                                   value="{{$filterParameters['maturity_date_to']}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="amount_condition">Interest Rate Condition</label>
                                                            <select name="interest_rate_condition" class="form-control " >
                                                                @foreach($amountConditions as $key=>$amount_codition)
                                                                    <option value="{{$amount_codition}}"{{ $amount_codition == $filterParameters['interest_rate_condition'] ?'selected' :''}}> {{ucwords($key)}}  </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Interest Rate</label>
                                                            <input type="number"  class="form-control" name="interest_rate" id="interest_rate"
                                                                   value="{{$filterParameters['interest_rate']}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="amount_condition">Invested Amount Condition</label>
                                                            <select name="amount_condition" class="form-control " >
                                                                @foreach($amountConditions as $key=>$amount_codition)
                                                                    <option value="{{$amount_codition}}"{{ $amount_codition == $filterParameters['amount_condition'] ?'selected' :''}}> {{ucwords($key)}}  </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Invested Amount</label>
                                                            <input type="number" min="0" class="form-control" name="invested_amount" id="invested_amount"
                                                                   value="{{$filterParameters['invested_amount']}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Referred By</label>
                                                            <input type="text"  class="form-control" name="referred_by" id="referred_by"
                                                                   value="{{$filterParameters['referred_by']}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Is Active</label>
                                                            <select name="is_active" class="form-control " id="is_active">
                                                                <option value="">Select All </option>
                                                                <option value="1" {{ isset($filterParameters['is_active']) && ($filterParameters['is_active'] == 1)?'selected':''}}>Active</option>
                                                                <option value="0"  {{ isset($filterParameters['is_active']) && ($filterParameters['is_active'] == 0)?'selected':''}}>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Status</label>
                                                            <select name="status" class="form-control " id="is_active">
                                                                <option value="">Select All </option>
                                                                <option value="pending" {{  ($filterParameters['status'] == 'pending')?'selected':''}}>Pending</option>
                                                                <option value="accepted"  {{  ($filterParameters['status'] == 'accepted')?'selected':''}}>Accepted</option>
                                                                <option value="rejected"  {{  ($filterParameters['status'] == 'rejected')?'selected':''}}>Rejected</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="payment_mode">Payment Mode</label>
                                                            <select name="payment_mode" class="form-control " id="payment_mode">
                                                                <option value="">Select All </option>
                                                                <option value="offline" {{  ($filterParameters['payment_mode'] == 'offline')?'selected':''}}>Offline</option>
                                                                <option value="online"  {{  ($filterParameters['payment_mode'] == 'online')?'selected':''}}>Online</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <button type="submit" id="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Investment Plan Subscribed : {{ (count($subscribedIP)>0) ? $subscribedIP[0]->investment_plan_name : ''}}
                            </h3>

                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
{{--                                    <th>Investment Name</th>--}}
                                    <th>Investment Holder Type</th>
                                    <th>Investment Holder Name</th>
                                    <th>Invested Amount(Rs.)</th>
                                    <th>Maturity Date</th>
                                    <th>Interest Rate(%)</th>
                                    <th>Referred By</th>
                                    <th>Subscribed At</th>
                                    <th>Is Active</th>
                                    <th>Status</th>
                                    <th>Payment Mode</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody id="investment-contents">
                                @php
                                    $status = [
                                            'pending' => 'warning',
                                            'accepted' => 'success',
                                            'rejected' => 'danger'
                                            ];
                                @endphp
                                @forelse($subscribedIP as $key => $subcriptionData)
{{--                                    {{dd($subcriptionData)}}--}}
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
{{--                                        <td>{{$subcriptionData->investment_plan_name}} </td>--}}
                                        <td>{{ucfirst($subcriptionData->investment_holder_type)}} </td>

                                        @if($subcriptionData->investment_holder_type == 'store')
                                            <td>{{($subcriptionData->storeHolderId) ? $subcriptionData->storeHolderId->store_name:''}} ({{$subcriptionData->investment_holder_id}}) </td>
                                        @elseif($subcriptionData->investment_holder_type == 'vendor')
                                            <td>{{($subcriptionData->vendorHolderId) ? $subcriptionData->vendorHolderId->vendor_name:''}} ({{$subcriptionData->investment_holder_id}}) </td>
                                        @elseif($subcriptionData->investment_holder_type == 'manager')
                                                <td>{{($subcriptionData->managerHolderId) ? $subcriptionData->managerHolderId->manager_name:''}} ({{$subcriptionData->investment_holder_id}}) </td>
                                        @else
                                            <td>{{($subcriptionData->userHolderId) ? $subcriptionData->userHolderId->name:''}} ({{$subcriptionData->investment_holder_id}}) </td>
                                        @endif

                                        <td>{{$subcriptionData->invested_amount}} </td>
                                        <td>{{$subcriptionData->maturity_date}} </td>
                                        <td>{{$subcriptionData->interest_rate}} </td>
                                        <td>{{($subcriptionData->user) ? $subcriptionData->user->name:'N/A'}} </td>
                                        <td>{{ date("d M Y", strtotime($subcriptionData->created_at)) }} </td>

                                        <td>
                                            @can('Change Investment Plan Subscription Status')
                                                <label class="switch">
                                                    <input class="toggleStatus" href="{{route('admin.investment-subscription.toggle-status',$subcriptionData->ip_subscription_code)}}" data-ISC="{{$subcriptionData->ip_subscription_code}}" type="checkbox" {{($subcriptionData->is_active) === 1 ?'checked':''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            @endcan
                                        </td>

                                        <td>
                                            <span class="label label-{{$status[$subcriptionData->status]}}">
                                            {{ucfirst($subcriptionData->status)}}
                                            </span>
                                        </td>
                                        <td>
                                            {{ucwords($subcriptionData->payment_mode)}}
                                        </td>

                                        <td>
                                            @can('Show Investment Plan Subscription')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.investment-subscription.show',$subcriptionData->ip_subscription_code),'View Detail', 'eye','primary')!!}
                                            @endcan
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
                            {{$subscribedIP->appends($_GET)->links()}}
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
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function (){
            $('.toggleStatus').on('change',function (event){
                event.preventDefault();
                var status = $(this).prop('checked') === true ? 1 : 0;
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Do you Want To Change subcription Status?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10em',
                    width:'500px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }else if (result.isDenied) {
                        if (status === 0) {
                            $(this).prop('checked', true);
                        } else if (status === 1) {
                            $(this).prop('checked', false);
                        }
                    }
                })
            });
        });
        $(".fancybox").fancybox();

        $
    </script>
@endpush

