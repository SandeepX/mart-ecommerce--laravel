<div class="panel panel-primary">
    <div class="panel-heading">
       <strong> Please Select the Products to Add to Purchase Order List </strong>
    </div>

    <div class="panel-body">

        
        <table class="table table-bordered" id="tbl_order">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Qty</th>
                    <th style="width: 5px;">Action</th>
                </tr>
            </thead>
            <tbody id="tbl_order_body">
                <tr id="rec-1">

                    <td style="width: 40%">
                        <select name="product_code[]" required class=" select2_product form-control" id="product_1">

                        </select>
                    </td>
                    <td style="width: 20%">
                        <select disabled name="product_variant_code[]" class="select2_product_variant form-control" id="product_variant_1"></select>
                    </td>
                    <td>
                        <input required type="number" id="qty" class="form-control" value="" name="qty[]">
                    </td>
                    <td>
                        <a id="minus_record" class="btn btn-danger btn-sm delete-record" data-id="1"><i class="fa fa-minus"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        
    </div>
</div>



