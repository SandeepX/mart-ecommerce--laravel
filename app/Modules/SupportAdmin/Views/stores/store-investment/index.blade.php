
<div class="card card-default bg-panel">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-12">
                        <div style="font-size:15px" class="panel-title">
                          <h4 style="margin-left: 10px;">
                              <strong>
                                  {{$filterParameters['store_name']}}({{$storeCode}}): Investment

                                  <a style="margin-right: 20px; margin-bottom: 10px;" class="btn btn-danger btn-sm pull-right" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseExample">
                                      <i class="fa  fa-filter"></i>
                                  </a>
                              </strong>
                            </h4>


                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="investment_plan_form" action="{{route('support-admin.store-investment',$storeCode)}}" method="get">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="">Investment Plan Name</label>
                                        <input type="text"  class="form-control" name="investment_plan_name" id="investment_plan_name"
                                               value="{{$filterParameters['investment_plan_name']}}">
                                    </div>
                                </div>

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

                                <button type="button" id="investment-filter-btn" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel panel-default">

                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Investment Plan Name</th>
                        <th>Invested Amount(Rs.)</th>
                        <th>Maturity Date</th>
                        <th>Interest Rate(%)</th>
                        <th>Referred By</th>
                        <th>Subscribed At</th>
                        <th>Is Active</th>
                        <th>Status</th>
                        <th>Investment Return At Maturity</th>

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

                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$subcriptionData->investment_plan_name}}</td>
                           <td>{{$subcriptionData->invested_amount}} </td>
                            <td>{{$subcriptionData->maturity_date}} </td>
                            <td>{{$subcriptionData->interest_rate}} </td>
                            <td>{{($subcriptionData->user) ? $subcriptionData->user->name:'N/A'}} </td>
                            <td>{{ date("d M Y", strtotime($subcriptionData->created_at)) }} </td>

                            <td>
                                <span class="badge  {{($subcriptionData->is_active) === 1 ? 'btn-success':'btn-danger'}}">
                                    {{($subcriptionData->is_active) === 1 ?'Active':'Inactive'}}
                                </span>
                            </td>

                            <td>
                                <span class="label label-{{$status[$subcriptionData->status]}}">
                                {{ucfirst($subcriptionData->status)}}
                                </span>
                            </td>

                            <td>
                                <div class="pull-left" style="margin-top: -5px;margin-right: 5px;">
                                    <button type="button" class="btn btn-info btn-xs"
                                            data-toggle="modal"
                                            data-target=".bd-example-modal-sm"
                                            value="{{$subcriptionData->ip_subscription_code}}"
                                            data-url="{{route('support-admin.investment-return.show',['ISCode'=> $subcriptionData->ip_subscription_code])}}"
                                            id="investment_return_view_btn"
                                    >
                                            view
                                    </button>
                                </div>
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

                <div class="pagination" id="investment-pagination">
                    @if(isset($subscribedIP))
                        {{ $subscribedIP->appends($_GET)->links() }}
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" id="view-investment-return-detail">

            </div>
        </div>
    </div>




























