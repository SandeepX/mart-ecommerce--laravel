<div class="col-xs-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                List of Products
            </h3>

        </div>


        <div class="box-body">


            <div id="product_list_tbl" style="display: block;height: 500px; overflow-y: auto; overflow-x: hidden;">
                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Stock Available</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($warehouseProducts as $warehouseProduct)
                       <tr>
                           @inject('productMaster', 'App\Modules\Product\Models\ProductMaster')
                           <td>
                               <img style="width:50px;height:50px" src="{{ photoToUrl($warehouseProduct->image, asset($productMaster->uploadFolder)) }}" alt="product-image">
                           </td>
                           <td>{{ $warehouseProduct->product_name." ".$warehouseProduct->product_variant_name }}</td>
                           <td>{{ $warehouseProduct->current_stock }}</td>
                           <td>
{{--                               <a href="javascript:void(0);" data-warehouse_product_master_code="{{ $warehouseProduct->warehouse_product_master_code }}" class="btn btn-sm btn-success product_class"><i class="fa fa-plus"></i></a>--}}
                               <button type="button" data-warehouse_product_master_code="{{ $warehouseProduct->warehouse_product_master_code }}" class="btn btn-sm btn-success product_class" data-toggle="modal" data-target="#stockTransferModal">
                                   <i class="fa fa-plus"></i>
                               </button>
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

                <div id="paginate_product_table">
                    @if(isset($warehouseProducts))
                        {{$warehouseProducts->appends($_GET)->links()}}
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
