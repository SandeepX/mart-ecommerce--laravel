
@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">

    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=> formatWords($title,true),
    'sub_title'=>'Manage '. formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'list'),
    ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">

                            <form action="{{route('admin.store.balance.list')}}" method="get">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="store_name">Store</label>
                                            <input type="text" class="form-control" name="store_name" id="store_name"
                                                   value="{{isset($filterParameters['store_name']) ? $filterParameters['store_name'] : ''}}">
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
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="province" class="control-label">Province  *</label>
                                            <select class="form-control" id="province" name="province" >
                                                <option selected value="" >--Select An Option--</option>
                                                @if(isset($provinces) && count($provinces)>0)
                                                    @foreach ($provinces as $province)
                                                        <option value={{ $province->location_code }} {{ $filterParameters['province'] == $province->location_code ? 'selected': '' }}>{{ $province->location_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="district" class="control-label">District  *</label>
                                            <select name="district" class="form-control" id="district" onchange="districtChange()">
                                                <option selected value="" >--Select An Option--</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="municipality" class="control-label">Municipality  *</label>
                                            <select name="municipality" class="form-control" id="municipality" onchange="municipalityChange()">
                                                <option selected value="" >--Select An Option--</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="ward" class="control-label">Ward  *</label>
                                            <select class="form-control" id="ward"  name="ward">
                                                <option selected value="" >--Select An Option--</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{ formatWords($title,true)}}
                            </h3>

                            <div class="pull-right" style="margin-top: -23px;margin-left: 10px;">
                                <a href="{{ route('admin.store.balance.export') }}" style="border-radius: 0px; " class="btn btn-sm btn-success">
                                    <i class="fa fa-file-excel-o"></i>
                                    Download Excel File
                                </a>
                            </div>
                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store Name</th>
                                    <th>Last Transaction Date</th>
                                    <th>Current Balance</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                @forelse($storeBalances as $key =>$balance)

                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{ucwords($balance->store_name)}} ( {{($balance->store_code)}} )</td>
                                        <td> {{ ($balance->last_transaction_date)
                                            ? date('d-M-Y',strtotime($balance->last_transaction_date))
                                            : 'N/A'
                                            }}</td>
                                        <td> Rs.{{ getNumberFormattedAmount($balance->store_current_balance) }} </td>
                                        <td>


                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.store.balance.detail',$balance->store_code ),'Detail Transaction', 'eye','primary')!!}



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
                            {{$storeBalances->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection
{{--@push('scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(function(){--}}
{{--            $('a[data-toggle="modal"]').click(function() {--}}
{{--                var target = $(this).attr('data-target');--}}
{{--                $(`${target} .modal-content`).html('');--}}
{{--                let url = $(this).attr('href');--}}
{{--                $(`${target} .modal-content`).load(url, function(result) {--}}
{{--                    $(target).show();--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--    @includeIf('Store::admin.scripts.store-filter-script');--}}
{{--@endpush--}}


@push('scripts')
    <script>
        $(document).ready(function(){
            $('a[data-toggle="modal"]').click(function() {
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });
        });
    </script>
@endpush
