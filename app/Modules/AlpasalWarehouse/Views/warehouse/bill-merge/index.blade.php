@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
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
                            <form action="{{route('warehouse.bill-merge.index')}}" method="get">

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="store_name">Store Name</label>
                                        <input type="text" class="form-control" name="store_name" id="store_name"
                                               value="{{$filterParameters['store_name']}}">
                                    </div>
                                </div>

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
                                List of {{  formatWords($title,true)}}
                            </h3>
                            @can('Create Bill Merge')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('warehouse.bill-merge.merge-form') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Merge Bills
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <div class="box-body">

                            <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Store Name</th>
                                    <th>Status</th>
                                    <th>Merging Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($mergedOrders as $i => $mergedOrder)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $mergedOrder->bill_merge_master_code}}</td>
                                        <td>{{ $mergedOrder->store->store_name}}</td>
                                        <td>{{ ucwords($mergedOrder->status) }}</td>
                                        <td>{{ getNepTimeZoneDateTime(getReadableDate($mergedOrder->created_at)) }}</td>
                                        <td>
                                            @can('Show Bill Merge Products')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View Product ', route('warehouse.merge-bill.product-lists', $mergedOrder->bill_merge_master_code),'Details', 'eye','info')!!}
                                            @endcan
                                            @can('Show Bill Merge Order Details')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View Merge Details ', route('warehouse.merge-bill.merge-order-details', $mergedOrder->bill_merge_master_code),'Details', 'file','primary')!!}
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
                            {{$mergedOrders->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
