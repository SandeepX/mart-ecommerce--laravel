
<form id="filter_form" action="{{ route('admin.warehouse-purchase-orders.index') }}" method="GET">
    <label class="control-label">Filter By Vendor</label>

    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <select name="filter_by" class="form-control select2" id="filter_by" required>
                    <option value="all" selected disabled>--Select An Option--</option>
                    <option value="all">All</option>
                    <option value="sent">Sent</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="in_process">In Process</option>
                    <option value="ready_for_dispatch">Ready For Dispatch</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">View Orders</button>
            </div>
        </div>
    </div>
</form>
