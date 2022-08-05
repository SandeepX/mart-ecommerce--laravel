@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'withdraw'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <br>
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">



                        <div class="panel-body">
                            <form action="{{route('admin.stores.balance-withdrawRequest.detail',$store->store_code)}}" method="get">



                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_from">Transation  Date From</label>
                                        <input type="date" class="form-control" name="transaction_date_from" id="transaction_date_from"
                                               value="">
                                    </div>

                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_to">Transaction Date To</label>
                                        <input type="date" class="form-control" name="transaction_date_to" id="transaction_date_to"
                                               value="">
                                    </div>
                                </div>


                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="verification_status">Transaction Type</label>
                                        <select name="transaction_type" class="form-control" id="transaction_type">
                                            <option value=" " >All</option>
                                            <option value="load_balance">Load Balance</option>
                                            <option value="withdraw">Withdraw</option>
                                        </select>
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

                            <div class="panel-title">
                                <span> List of Transactions of  Store : {{$store->store_name}} / {{$store->store_code}}</span>
                                <span class=" pull-right">Current Balance : {{number_format($currentBalance['balance'])}}</span>
                            </div>

                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>Amount</th>
                                    <th>Total Current Balance</th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse($allTransactionByStoreCode as $i => $allTransaction)

                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{date('d-M-Y',strtotime($allTransaction['created_at']))}}</td>
                                        <td>{{$allTransaction['transaction_type']}}</td>
                                        <td> Rs. {{number_format($allTransaction['transaction_amount'])}}</td>
                                        <td>{{$allTransaction['current_transaction_balance']}}</td>
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

                            {{ $allTransactionByStoreCode->links() }}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
