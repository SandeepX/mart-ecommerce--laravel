@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.reconciliation'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">


                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="{{route('admin.balance.reconciliation')}}" method="get">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="balance_reconciliation_code">Balance Reconciliation Code</label>
                                        <input type="text" class="form-control" name="balance_reconciliation_code" id="balance_reconciliation_code"
                                               value="{{($filterParameters['balance_reconciliation_code'])}}">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="transaction_from">Transacation From</label>
                                        <input type="date" class="form-control" name="transaction_from" id="transaction_from"
                                               value="{{($filterParameters['transaction_from'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="transaction_to">Transaction To </label>
                                        <input type="date" class="form-control" name="transaction_to" id="transaction_to"
                                               value="{{($filterParameters['transaction_to'])}}">
                                    </div>
                                </div>


                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="transaction_type">Transaction Type</label>
                                        <select name="transaction_type" class="form-control" id="transaction_type">
                                            <option value="">Select All </option>
                                            <option value="withdraw" {{($filterParameters['transaction_type'] =="withdraw")?'selected':''}}>Withdraw</option>
                                            <option value="deposit" {{($filterParameters['transaction_type'] =="deposit")?'selected':''}}>Deposit</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Through</label>
                                        <select name="payment_method" class="form-control " id="payment_method">
                                            <option value="">Select All </option>
                                            <option value="bank"    {{($filterParameters['payment_method'] =="bank")?'selected':''}}>Bank</option>
                                            <option value="remit"     {{($filterParameters['payment_method'] =="remit")?'selected':''}}>Remit</option>
                                            <option value="digital_wallet"     {{($filterParameters['payment_method'] =="digital_wallet")?'selected':''}}>Digital Wallet</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-xs-4" id="payment_name" >
                                    <div class="form-group">
                                        <label for="payment_method" >Payment Body</label>
                                        <select class="form-control"  id="payment_body_code" name="payment_body_code"  autocomplete="off">
                                            <option value="{{($filterParameters['payment_method_name'])}}">{{$filterParameters['payment_method_name']}}</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="transaction_no ">Transaction Number </label>
                                        <input type="text" class="form-control" name="transaction_no" id="transaction_no "
                                               value="{{($filterParameters['transaction_no'])}}">
                                    </div>
                                </div>


                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="transacted_by ">Description </label>
                                        <input type="text" class="form-control" name="description" id="description"
                                               value="{{($filterParameters['description'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="amount_condition">Amount Condition</label>
                                        <select name="amount_condition" class="form-control " id="amount_condition">
                                            @foreach($amountConditions as $key=>$amount_codition)
                                                <option value="{{$amount_codition}}"{{ $amount_codition == $filterParameters['amount_condition'] ?'selected' :''}}> {{ucwords($key)}}  </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="transaction_amount">Transaction Amount</label>
                                        <input type="number" min="0" class="form-control" name="transaction_amount" id="transaction_amount"
                                               value="{{$filterParameters['transaction_amount']}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="payment_method">Reconciliation status</label>
                                        <select name="status" class="form-control " id="status">
                                            <option value="">Select All </option>
                                            <option value="used" {{($filterParameters['status'] =="used")?'selected':''}}>Used</option>
                                            <option value="unused"  {{($filterParameters['status'] =="unused")?'selected':''}}>Unused</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="created_from">Created From</label>
                                        <input type="date" class="form-control" name="created_from" id="created_from"
                                               value="{{($filterParameters['created_from'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="created_to">Created To </label>
                                        <input type="date" class="form-control" name="created_to" id="created_to"
                                               value="{{($filterParameters['created_to'])}}">
                                    </div>
                                </div>

                                <button type="submit" id="submit" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>

                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Balance Reconciliation Statement
                            </h3>

                            @can('Create Store Balance Reconciliation')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.balance.reconciliation.get-import-page')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Import Balance Reconciliation
                                    </a>
                                    <a href="{{route('admin.balance.reconciliation.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Balance Reconciliation
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code<br>(created_at)</th>
                                    <th>Transaction Date</th>
                                    <th>Transaction type</th>
                                    <th>Transaction Amount</th>
                                    <th>Description</th>
                                    <th>Payment Through</th>
                                    <th>Payment Body</th>
                                    <th>Transaction Number</th>
{{--                                    <th>Remark</th>--}}
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>


                                @forelse($balanceReconciliationDetail as $key => $detail)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            {{$detail->balance_reconciliation_code}}<br>
                                            ({{ getReadableDate($detail->created_at,'Y-m-d') }})
                                        </td>
                                        <td> {{ getReadableDate($detail->transaction_date,'Y-M-d') }}</td>
                                        <td>{{ucfirst($detail->transaction_type)}}</td>
                                        <td>{{ getNumberFormattedAmount($detail->transaction_amount) }} </td>
                                        <td>{!!strip_tags( $detail->description )!!}</td>
                                        <td>{{ucfirst($detail->payment_method)}}</td>

                                        @if($detail['payment_method']=='bank')
                                            <td>{{$detail->getBankname ? ucfirst($detail->getBankname->bank_name) : '-'}}</td>

                                        @elseif($detail['payment_method']=='remit')
                                            <td>{{$detail->getRemitName ? ucfirst($detail->getRemitName->remit_name) : '-'}}</td>


                                        @elseif($detail['payment_method'] =='digital_wallet')
                                            <td>{{$detail->getDigitalWalletName ? ucfirst($detail->getDigitalWalletName->wallet_name) : '-'}}</td>

                                        @endif

                                        <td>{{$detail->transaction_no }}</td>

{{--                                        <td>{{($detail->balanceReconciliationUsage->balanceReconciliationUsageRemarks))?--}}
{{--                                                $detail->balanceReconciliationUsage->balanceReconciliationUsageRemarks->remark:'N/A'--}}
{{--                                                }}--}}
{{--                                        </td>--}}

                                        <td>
                                            @canany('Show Store Balance Reconciliation')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.balance.reconciliation.show',$detail->balance_reconciliation_code),'Detail Balance reconciliation', 'eye','primary')!!}
                                            @endcanany

                                            @if($detail->status =='unused')

                                                @canany('Update Store Balance Reconciliation')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit', route('admin.balance.reconciliation.edit',$detail->balance_reconciliation_code),'edit Balance reconciliation', 'pencil','warning')!!}
                                                @endcanany

                                                {{--                                                @canany('Delete Balance Reconciliation Detail')--}}
                                                {{--                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.balance.reconciliation.destroy',$detail->balance_reconciliation_code),$detail,'Balance reconciliation','Balance Reconciliation' )!!}--}}
                                                {{--                                                @endcanany--}}

                                                @canany('Change Store Balance Reconciliation Status')
                                                    {{--                                                        <a href="{{route('admin.balance.reconciliation.change-status',$detail->balance_reconciliation_code)}}">--}}
                                                    <button class="label label-success changeStatus" data-value="{{$detail->balance_reconciliation_code}}" >
                                                        Change Status to Used
                                                    </button>
                                                    {{--                                                        </a>--}}
                                                @endcanany


                                            @else

                                                @if($detail->balanceReconciliationUsage && $detail->balanceReconciliationUsage->used_for == 'locked')
                                                 <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#balance-reconciliation-remarks-{{$detail->balance_reconciliation_code}}">
                                                     Remarks
                                                 </button>
                                                        <!-- Modal -->
                                                  @include(''.$module.'BalanceManagement.balance-reconciliation.usages-remarks-update-modal')

                                                    @endif

                                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#balance-reconciliation-{{$detail->balance_reconciliation_code}}">
                                                        Already Used
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="balance-reconciliation-{{$detail->balance_reconciliation_code}}" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Balance Reconciliation Code: {{$detail->balance_reconciliation_code}}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                      @if($detail->status =='used')
                                                                          @if($detail->balanceReconciliationUsage)
                                                                               @if($detail->balanceReconciliationUsage->used_for =='locked')
                                                                                   &nbsp;&nbsp;&nbsp;&nbsp; Status : {{$detail->balanceReconciliationUsage->used_for}}
                                                                                   @if($detail->balanceReconciliationUsage->balanceReconciliationUsageRemarks)
                                                                                     <br/>
                                                                                <div class="col-md-2">
                                                                                    Remarks:
                                                                                </div>

                                                                                <div class="col-md-10">
                                                                                    @foreach($detail->balanceReconciliationUsage->balanceReconciliationUsageRemarks as $remarks)
                                                                                    <li>{{$remarks->remark}} ({{getReadableDate(getNepTimeZoneDateTime($remarks->created_at))}}) </li>
                                                                                    @endforeach
                                                                                </div>
                                                                                   @endif
                                                                               @else
                                                                                   Used For : {{ ucwords(str_replace('_',' ' ,$detail->balanceReconciliationUsage->used_for))}} (<a href="{{route('admin.wallet.offline-payment.load-balance.show',$detail->balanceReconciliationUsage->used_for_code)}}" target="_blank">{{$detail->balanceReconciliationUsage->used_for_code}}</a>)
                                                                               @endif
                                                                          @endif
                                                                      @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            @endif

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
                            {{ $balanceReconciliationDetail->appends($_GET)->links() }}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>


    @push('scripts')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function () {
                $('#payment_method').on('change',function(e) {
                    $('#payment_name').show();
                    var method = e.target.value;
                    var payment_body_code = $('#payment_method').val();

                    $('#payment_body_code').empty();
                    if(method!==''){
                        $('#payment_name').show();
                    }else{
                        $('#payment_name').hide();
                    }
                    $.ajax({
                        url:"{{route('admin.balance.reconciliation.getpayment-body')}}",
                        type:"POST",
                        data: {
                            payment_method:method,
                            payment_body_code:payment_body_code,
                            _token: '{{csrf_token()}}'
                        },
                        success:function(data) {
                            $('#payment_body_code').append(data);

                        }
                    })

                });
                $('#payment_method').trigger('change');

                $('.changeStatus').on('click',function(event){
                    event.preventDefault();
                    var reconciliationCode = $(this).attr('data-value');
                    (async () => {
                       await  Swal.fire({
                        title: 'Are you sure you want to change status to used?',
                        text: "You won't be able to revert this!",
                        html: '<textarea required id="remarks" rows="5" style="height:auto!important" name="remarks" class="swal2-input" placeholder="Type your remarks here..."></textarea>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        padding: '5em',
                        width:'500px',
                        confirmButtonText: 'Yes, Change it!',
                        preConfirm: () => {
                             let remarksValue =document.getElementById('remarks').value;
                             if (remarksValue){
                                    return {
                                        'remarks':document.getElementById('remarks').value,
                                    }
                             }
                             else{
                                 Swal.showValidationMessage('Remarks is required.');
                             }
                        }
                        }).then((result) => {

                            if (result.isConfirmed) {
                                let remarks = result.value.remarks;
                                $.ajax({
                                    url:"{{route('admin.balance.reconciliation.change-status')}}",
                                    type:"POST",
                                    data: {
                                        reconciliationCode : reconciliationCode,
                                        remarks: remarks,
                                        _token: '{{csrf_token()}}'
                                    },
                                    success:function(data) {
                                        location.reload();
                                    }
                                })
                            }
                        })
                    })()
                });

            });


            $('.fromBalanceReconciliationUpdateRemarks').on('submit',function (e){
                        e.preventDefault();
                        Swal.fire({
                        title: 'Do you want to update Remarks?',
                        showCancelButton: true,
                        customClass: 'swal-wide',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes`,
                        cancelButtonText: `No`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
               });


        </script>



    @endpush

@endsection
