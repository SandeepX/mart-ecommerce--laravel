@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <form action="{{route('warehouse.warehouse-pre-orders.stores')}}" method="get">

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="store_name">Store Name</label>
                                            <input type="text" class="form-control" name="store_name" id="store_name"
                                                   value="{{$filterParameters['store_name']}}">
                                        </div>
                                    </div>

                                   {{-- <div class="col-xs-4">
                                        <label for="status">Status</label>
                                        <select class="form-control select2" id="status" name="status[]" multiple>
                                          --}}{{--  <option value="" readonly>Select All</option>--}}{{--

                                            @foreach($preOrderStatuses as $preOrderStatus)
                                                <option value="{{$preOrderStatus}}"
                                                    {{(isset($filterParameters['statuses']) && in_array($preOrderStatus,$filterParameters['statuses']))? 'selected' :''}}>
                                                    {{ucwords($preOrderStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>--}}

                                    <br><br>
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
                                List of Stores
                            </h3>

                        </div>

{{--                       @can('View Store Pre Orders in Pre Order')--}}
                            <div class="box-body">

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <th>#</th>
                                        <th>Store Name</th>
                                        <th>No.of Preorders</th>
                                        <th>Amounts</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($stores as $i => $store)

                                        <tr>

                                            <td>{{++$i}}</td>
                                            <td>
                                                {{$store->store_name}}({{$store->store_code}})
                                            </td>
                                            <td>{{$store->total_preorders}}</td>
                                            <td>
                                                 <span class="label label-primary">
                                                    Finalized</span> : {{$store->finalized_total_price}}<br>
                                                <span class="label label-success">
                                                    Dispatched
                                                </span> : {{$store->dispatched_total_price}}<br>
                                                <span class="label label-warning">
                                                    Pending
                                                </span>: {{$store->pending_total_price}}
                                                <br>
                                                <span class="label label-danger">
                                                    Cancelled
                                                </span>: {{$store->cancelled_total_price}}

                                            </td>

                                            <td>
                                                @can('View Store Pre Orders In Pre Order')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-pre-orders.stores.detail', $store->store_code),'Details', 'eye','info')!!}
                                                @endcan
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <p class="text-center"><b>No records found!</b></p>
                                            </td>

                                        </tr>
                                    @endforelse
                                    </tbody>


                                </table>
                                {{$stores->appends($_GET)->links()}}
                            </div>
{{--                        @endcan--}}
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

