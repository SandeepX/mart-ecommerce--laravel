<!-- general form elements -->
<div class="box box-primary">
    <!-- form start -->
    <form class="form-horizontal" id="warehouse-purchase-order-form">
        <div class="box-header with-border" style="padding-bottom: 0px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <h3 class="box-title text-primary"><i class="fa fa-shopping-cart text-aqua"></i> Purchase Order
                        </h3>
                    </div>


                </div>
            </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            {{--<div class="row">
                <div class="col-md-12">
                    <label for="warehouse_code">Warehouse</label>
                    <select class="select2 form-control" id="warehouse_code" name="warehouse_code">
                        <option selected disabled> -- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{$warehouse->warehouse_code}}">{{$warehouse->warehouse_name}}</option>
                        @endforeach
                    </select>

                </div>

            </div>

            <br>--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-12" style="overflow-y: auto; height: 350px;">
                            <table id="product-order-list-tbl"
                                   class="table table-condensed table-bordered table-striped table-responsive items_table">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width:30%">Product</th>
                                    <th style=" width:7%">Variant</th>
                                    <th style=" width:7%">Package Type</th>
                                    <th style=" width:7%">Qty</th>
                                    <th style="width:15%">Price</th>
                                    <th style="width:15%">Subtotal</th>
                                    <th style="width:5%">Action</th>
                                </tr>
                                </thead>
                                <tbody id="product-order-list-tbl-body" style="font-size: 16px;font-weight: bold;overflow: scroll;">

                                @if(isset($purchaseOrderDetails))
                                    @foreach($purchaseOrderDetails as $purchaseOrderDetail)
                                        <tr>
                                            <td>
                                                {{$purchaseOrderDetail['product']['product_name']}}
                                            </td>
                                            <td style="width: 20%">
                                                <select name="product_variant_code[]" class="select2_product_variant form-control">
                                                    @if(count($purchaseOrderDetail['product']['productVariants']) > 0)
                                                        <option readonly value="">Select Variant</option>
                                                        @foreach($purchaseOrderDetail['product']['productVariants'] as $productVariant)
                                                            <option value="{{$productVariant->product_variant_code}}"
                                                                {{$productVariant->product_variant_code == $purchaseOrderDetail['product_variant_code'] ? 'selected' :''}}>
                                                                {{$productVariant->product_variant_name}}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="" selected readonly>No Variant</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input class="quantity-input" style="width:75px" min="1" required name="quantity[]" type="number"
                                                       value="{{$purchaseOrderDetail['quantity']}}">
                                            </td>
                                            <td>
                                                {{$purchaseOrderDetail['mrp']}}
                                            </td>
                                            <td>
                                                {{$purchaseOrderDetail['quantity'] * $purchaseOrderDetail['mrp']}}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger delete-order-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                               {{-- <tr>
                                    <td>
                                        Gyan Suji
                                        <br>
                                        <small>variant-1</small>
                                    </td>

                                    <td>
                                        <input style="width:75px" type="number" value="1">
                                    </td>
                                    <td>
                                        4500
                                    </td>
                                    <td>
                                        4500
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-order-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>--}}

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer bg-gray">

            <div class="row">
                <div class="col-md-12 text-right">
                    <div class="col-sm-6">
                        <button  type="submit" id="hold_invoice" value="draft" class="save_purchase_order btn bg-maroon btn-block btn-flat btn-lg"
                                title="Hold Purchase Order (Not Submitted to Vendor : Can Edit The Purchase Order Later On )">
                            <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                            Save as Draft
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" id="submit-btn" value="sent" class="save_purchase_order btn btn-primary btn-block btn-flat btn-lg"
                                title="Directly Submit the Purchase Order to Vendor (Cannot Edit The Purchase Order After Direct Submit)">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /.box -->
