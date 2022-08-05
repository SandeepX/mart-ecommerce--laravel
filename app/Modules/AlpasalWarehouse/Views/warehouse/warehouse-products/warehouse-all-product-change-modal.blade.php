<div class="modal fade" id="warehouseProductStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                 <strong class="modal-title warehouseName" id="exampleModalLabel">Change status of All product of this warehouse</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="showFlashMessageModal"></div>
                <form method="post" id="warehouseProduct" >
                    @csrf

                    <div class="form-group">
                        <label class="control-label">Change Status</label>
                        <select  class="form-control input-sm" name="is_active" id="warehouseStatus" required autocomplete="off">
                            <option value="">select status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="changeWarehouseProductStatus" class="btn btn-success">Change</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
