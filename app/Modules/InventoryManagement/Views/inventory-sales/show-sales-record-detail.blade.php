@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage Stock Sales Record Detail",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel-heading">
                        <div class="card" >
                            <div class="card-body">
                                <h4 class="card-title" >
                                    <strong> Store Inventory Stock Batch Detail : {{$inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->storeInventory->productDetail->product_name}} {{($inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->storeInventory->productVariantDetail)?
                                                                $inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->storeInventory->productVariantDetail->product_variant_name:''}}</strong>
                                </h4>
                                <div class="col-xs-2">
                                    <p><strong>Cost Price :</strong>  Rs.{{$inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->cost_price}}</p>
                                </div>
                                <div class="col-xs-2">
                                    <p><strong>MRP: </strong> Rs.{{$inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->mrp}} </p>
                                </div>
                                <div class="col-xs-4">
                                    <p><strong>Manufactured Date:</strong> {{$inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->manufacture_date}} </p>
                                </div>
                                <div class="col-xs-4">
                                    <p><strong>Expiry Date:</strong> {{$inventoryStockSalesRecordDetail[0]->storeInventoryItemDetail->expiry_date}} </p>
                                </div>
                                <div class="col-xs-6">
                                    <p><strong>Package configuration:</strong> {{ $packageDetail }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Store Inventory Stock Dispatched Sales Record  Detail
                            </h3>
                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Quantity</th>
                                    <th>Selling Price(Rs.)</th>
                                    <th>Payment Type</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($inventoryStockSalesRecordDetail as $key => $datum)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> <span class="label label-info">{{($datum->quantity)}} {{($datum->packageTypeDetail->package_name)}}</span></td>
                                        <td> {{number_format($datum->selling_price)}}</td>
                                        <td> <span class="badge btn-success">{{ucfirst($datum->payment_type)}}</span> </td>
                                        <td> {{ date('d-M-Y',strtotime($datum->created_at))}}</td>
                                        <td> {{ date('d-M-Y',strtotime($datum->updated_at))}}</td>

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



@endpush
