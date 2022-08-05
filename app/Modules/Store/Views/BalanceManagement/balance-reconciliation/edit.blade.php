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
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Update  {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.balance.reconciliation') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="editBalanceReconcilaition" action="{{route('admin.balance.reconciliation.update',$reconciliationDetail->balance_reconciliation_code)}}" enctype="multipart/form-data" method="post">
                                @method('put')
                                {{csrf_field()}}

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Transaction Type</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2" id="transaction_type"  name="transaction_type" required autocomplete="off">

                                                <option {{($reconciliationDetail->transaction_type =='withdraw')? 'selected':''}} value ="withdraw" )?>Withdraw</option>
                                                <option {{($reconciliationDetail->transaction_type =='deposit')? 'selected':''}} value ="deposit">Deposit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Payment Method</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2" id="payment_method"   name="payment_method" required autocomplete="off">

                                                <option {{($reconciliationDetail->payment_method =='bank')? 'selected':''}} value ="bank" data-name="Bank">Bank</option>
                                                <option {{($reconciliationDetail->payment_method =='remit')? 'selected':''}} value ="remit" data-name="Remit">Remit</option>
                                                <option {{($reconciliationDetail->payment_method =='digital_wallet')? 'selected':''}} value ="digital_wallet" data-name="Digital Wallet">Digital Wallet</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group payment_body_code">
                                        <label  class="col-sm-2 control-label payment-body">Payment Body Code</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="payment_body_code" name="payment_body_code" required autocomplete="off">

                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" id="payment_body" value="{{$reconciliationDetail->payment_body_code}}" name="payment_body" />

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Transaction Number</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{ $reconciliationDetail->transaction_no}}" name="transaction_no">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Transaction Amount</label>
                                        <div class="col-sm-6">
                                            <input type="number" min="1" class="form-control" value="{{$reconciliationDetail->transaction_amount}}" name="transaction_amount">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Transacted By</label>
                                        <div class="col-sm-6">
                                            <input type="transacted_by" class="form-control" value="{{$reconciliationDetail->transacted_by}}" name="transacted_by">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-6">
                                            <textarea id="description" class="form-control" name="description" required autocomplete="off" >{{$reconciliationDetail->description}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label"> Transaction Date</label>
                                        <div class="col-sm-6">
                                            <input type="date" class="form-control" value="{{$reconciliationDetail->transaction_date}}" name="transaction_date">
                                        </div>
                                    </div>


                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary updateBalanceReconcilition">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

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
                    var method = e.target.value;
                    var body_code = $('#payment_body').val();
                    //alert(body_code);

                    let payment_method_name=$(this).find(':selected').attr('data-name');

                    $('#payment_body_code').empty();
                    if(method!==''){
                        $('.payment_body_code').show();
                        $(".payment-body").text( 'Choose ' + payment_method_name);
                    }else{
                        $('.payment_body_code').hide();
                    }
                    $.ajax({
                        url:"{{route('admin.balance.reconciliation.getpayment-body.update')}}",
                        type:"POST",
                        data: {
                            payment_method:method,
                            body_code:body_code,
                            _token: '{{csrf_token()}}'
                        },
                        success:function(data) {

                            $('#payment_body_code').append(data);
                        }
                    })

                });
                $('#payment_method').trigger('change');
            });

            $('#editBalanceReconcilaition').submit(function (e, params) {
                var localParams = params || {};

                if (!localParams.send) {
                    e.preventDefault();
                }


                Swal.fire({
                    title: 'Are you sure you want to edit balance reconciliation detail ?',
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




@endsection
