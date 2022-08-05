<div class="modal fade" id="cloneByVendor" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clone Products From Vendor In This Pre Order Listing: <strong> {{$warehousePreOrder->warehouse_preorder_listing_code}}</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="showFlashMessageModal"></div>
            </div>
            <form id="clone-products-by-vendor-code" method="POST" action="{{route('warehouse.warehouse-pre-orders.clone-products.vendor-code',['preOrderListingCode'=>$warehousePreOrder->warehouse_preorder_listing_code])}}">

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="vendor_code">Select Vendor To Clone Product</label> <br>
                                <select id="vendor_code" name="vendor_code" class="form-control select2" style="width: 450px;">
                                    <option value="">
                                        Please Select
                                    </option>

                                    @foreach($vendors as $vendor)
                                        <option value="{{$vendor->vendor_code}}">
                                            {{ucwords($vendor->vendor_name)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="clone-button-by-vendor-code" class="btn btn-primary">Clone</button>
                </div>
            </form>
        </div>
    </div>
</div>
