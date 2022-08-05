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
                                @include(''.$module.'warehouse.current-stock.common.filter-form')
                            </div>
                        </div>
                    </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>
                        </div>

                            <div class="box-body">

                                <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vendor Name</th>
                                        <th>Total Products</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($vendorWiseCurrentStocks as $i => $vendorWiseCurrentStock)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ucfirst($vendorWiseCurrentStock->vendor_name) }}</td>
                                            <td>{{ $vendorWiseCurrentStock->total_products }}</td>
                                            <td>
                                                @can('Show WH Vendor Wise Current Stock Detail')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ', route('warehouse.vendor-wise.current-stock.detail', $vendorWiseCurrentStock->vendor_code),'Details', 'eye','info')!!}
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
                                {{$vendorWiseCurrentStocks->appends($_GET)->links()}}
                            </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
