 <div class="panel panel-success">
    <div class="panel-heading">
       <strong> Purchase Order List</strong>
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

        <div class="row">
            <div style="text-align:center;" class="col-md-12">
                <button id="add_row" class="btn btn-info pull-left">+ Add Row</button>

            </div>
        </div>
    </div>
</div>
<div>
    <button id="save_as_draft" class="btn btn-warning" type="submit">Save as Draft</button> 
    <button id="submit_order" class="btn btn-success" type="submit">Submit</button>
</div>
<div>
    <input type="hidden" id="submit_type" name="submit_type">
</div>


