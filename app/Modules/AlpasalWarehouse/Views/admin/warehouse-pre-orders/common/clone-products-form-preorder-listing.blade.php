
<form id="clone-form" action="{{ route('admin.warehouse-pre-orders.clone-products.source-to-destination') }}" method="POST">
    @csrf
    <div class="row">
            <h4 style="margin-left: 12px;">Clone Products From Source To Destination Pre Order Listing</h4>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="warehouse_name">Source Code</label>
                <input type="text" class="form-control" name="source_listing_code" id="source_listing_code"
                        placeholder="Source Pre Order Listing Code" required>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="warehouse_name">Destination Code</label>
                <input type="text" class="form-control" name="destination_listing_code" id="destination_listing_code" placeholder="Destination Pre Order Listing Code" required>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <button type="button" id="clone-button" class="btn btn-block btn-primary form-control">Clone</button>
            </div>
        </div>
    </div>
</form>
