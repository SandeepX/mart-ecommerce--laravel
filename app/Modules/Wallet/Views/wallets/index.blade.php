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
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.wallets.index')}}" method="get">

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="wallet_type">Wallet Type</label>
                                        <select name="wallet_type" class="form-control select2" id="wallet_type">
                                            <option value="" {{ $filterParameters['wallet_type']=='' ? 'selected':'' }}>All</option>
                                            @foreach($walletTypes as $walletType)
                                                <option value="{{$walletType}}" {{$walletType == $filterParameters['wallet_type'] ? 'selected' :''}}>
                                                    {{ucwords($walletType)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="current_balance_order">Order By Current Balance</label>
                                        <select name="current_balance_order" class="form-control select2" id="current_balance_order">
                                            <option value="" selected>Select Order</option>
                                            <option value="high_to_low" {{isset($filterParameters['current_balance_order']) && $filterParameters['current_balance_order']=="high_to_low" ?'selected' :''}}>
                                                high_to_low
                                            </option>
                                            <option value="low_to_high" {{isset($filterParameters['current_balance_order']) && $filterParameters['current_balance_order']=="low_to_high" ?'selected' :''}}>
                                                low_to_high
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6" style="display: none" id="wallet_name_section">
                                    <div class="form-group">
                                        <label for="wallet_name">Wallet Name</label>
                                        <input type="text" class="form-control" name="wallet_name" id="wallet_name"
                                               value="{{isset($filterParameters['wallet_name']) ? $filterParameters['wallet_name'] : ''}}">
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
                            <h3 class="panel-title">
                                List of {{ isset($filterParameters['wallet_type']) ? ucwords($filterParameters['wallet_type']) : '' }} Wallets
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">

                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Wallet Code</th>
                                    <th>Holder Type</th>
                                    <th>Name</th>
                                    <th>Current Balance</th>
                                    <th>Last Balance</th>
                                    <th>Last Transaction Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $active = [
                                             1=>'success',
                                             0=>'danger'
                                      ];
                                @endphp
                                @forelse($wallets as $key =>$wallet)
                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{$wallet->wallet_code}}</td>
                                        <td>{{ ucwords($wallet->wallet_type)}}</td>
                                        <td>{{$wallet->holder_name}} ({{$wallet->wallet_holder_code}})</td>
                                        <td>{{getNumberFormattedAmount($wallet->current_balance)}}</td>
                                        <td>{{getNumberFormattedAmount($wallet->last_balance)}}</td>
                                        <td>
                                            {{isset($wallet->last_transaction_date) ? getReadableDate($wallet->last_transaction_date) : 'N/A' }}
                                        </td>
                                        <td>
{{--                                            <a href="{{route('admin.wallets.transactions-purpose.toggleStatus',$wallet->wallet_transaction_purpose_code)}}" class=" changeStatus">--}}
                                                <span class="label label-{{$active[$wallet->is_active]}}">{{($wallet->is_active) ? 'Active' : 'Inactive'}}</span>
{{--                                            </a>--}}
                                        </td>
                                        <td>
                                            @if($wallet->wallet_type =='store')
                                                @can('View Store Wallet Transaction Detail')
                                               {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.wallet.transactions.store.details',$wallet->wallet_code ),'Detail Transaction Store', 'eye','primary')!!}
                                                @endcan
                                            @endif
                                            @if($wallet->wallet_type =='manager')
                                                    @can('View Manager Wallet Transaction Detail')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.wallet.transactions.manager.details',$wallet->wallet_code ),'Detail Transaction Manager', 'eye','primary')!!}
                                                    @endcan
                                            @endif
                                            @if($wallet->wallet_type =='vendor')
                                                @can('View Vendor Wallet Transaction Detail')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.wallet.transactions.vendor.details',$wallet->wallet_code ),'Detail Transaction Vendor', 'eye','primary')!!}
                                                @endcan
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
                            {{$wallets->appends($_GET)->links()}}
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
    <script>
        var walletType = $('#wallet_type');
        walletType.on('change',function (){
            var wallet_type = walletType.val();
             if(wallet_type){
                 $('#wallet_name_section').show();
             }else{
                 $('#wallet_name').val('');
                $('#wallet_name_section').hide();
             }
        });

        var wallet_type_val = walletType.val();
        if(wallet_type_val) {
            walletType.trigger('change');
        }
    </script>

@endpush
