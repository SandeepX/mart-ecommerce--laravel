@if(isset($warehousePreOrderProducts) && count($warehousePreOrderProducts)>0)
{{--    <div class="pull-right">--}}
{{--        <form  id="bulk-delete" action="{{route('warehouse.warehouse-pre-orders.product.bulk-destroy',$warehousePreOrderProducts[0]->warehouse_preorder_listing_code)}}" method="POST">--}}
{{--            <input type="hidden" name="_method" value="DELETE">--}}
{{--            <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
{{--            <button type="button" class="btn btn-danger btn-sm bulk-delete" >--}}
{{--                <i class="fa fa-trash"> Delete All products</i>--}}
{{--            </button>--}}
{{--        </form>--}}
{{--    </div>--}}

    @can('Change Status Of Products Of Vendor')
        <div class="pull-left">
            <button type="button" class="btn btn-success btn-sm changeStatus" data-toggle="modal" data-whplc = "{{ $warehousePreOrderProducts[0]->warehouse_preorder_listing_code }}" data-target="#warehousePreorderProductStatusModal" >
                <i class="fa fa-question-circle"> change status of All product</i>
            </button>
        </div>

        <div class="pull-left">
            <button type="button" class="btn btn-success btn-sm changeMicroPackaging" data-toggle="modal" data-whplc = "{{ $warehousePreOrderProducts[0]->warehouse_preorder_listing_code }}" data-target="#warehousePreorderProductMicroDisableModal" >
                <i class="fa fa-question-circle"> Disable/Enable Micro Packaging Of All product</i>
            </button>
        </div>
    @endcan
@endif

<table class="table table-bordered table-striped" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Product</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody id="pre-order-list-tbl-body">
    @if(isset($warehousePreOrderProducts))

        @foreach($warehousePreOrderProducts as $preOrderProduct)
            <tr>

                <td>{{$preOrderProduct->product->product_name}}</td>
                <td>
                    @if($preOrderProduct->total_active_product > 0)
                        <span class="label label-success">On</span>
                    @else
                        <span class="label label-danger">Off</span>
                    @endif
                </td>
                <td>
                    @can('Edit Product Variant Price Of Pre Order')
                        <button type="button" class="btn btn-primary btn-sm edit-variant-btn"
                                data-wpop-code="{{$preOrderProduct['warehouse_preorder_product_code']}}"
                                data-wpol-code="{{$preOrderProduct['warehouse_preorder_listing_code']}}"
                                data-product-code="{{$preOrderProduct['product_code']}}"
                        >
                            Edit Variant
                        </button>

                        <button type="button" class="btn btn-primary btn-sm package-disable-btn"
                                data-wpop-code="{{$preOrderProduct['warehouse_preorder_product_code']}}"
                                data-wpol-code="{{$preOrderProduct['warehouse_preorder_listing_code']}}"
                                data-product-code="{{$preOrderProduct['product_code']}}"
                        >
                            Disable Unit
                        </button>
                    @endcan


                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('warehouse.warehouse-pre-orders.product.delete-by-product-code', [
                        'warehousePreOrderListingCode' => $preOrderProduct['warehouse_preorder_listing_code'],
                        'preOrderProductCode'=> $preOrderProduct['product_code']
                        ]),$preOrderProduct,'PreOrder product',$preOrderProduct->product->product_name . '('.$preOrderProduct['product_code'].')' )!!}

            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<div id="pre-order-products-tbl-pagination">
    @if(isset($warehousePreOrderProducts))
        {{$warehousePreOrderProducts->appends($_GET)->links()}}
    @endif
</div>

@include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.warehouse-change-preorder-status-modal')
@include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.disable-enable-micro-packaging-modal')



