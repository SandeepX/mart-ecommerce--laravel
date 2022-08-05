<style>
    .form-group{
        margin-bottom: 6px !important;
    }
    .alert{
        padding: 5px !important;
    }
    .swal-wide{
        width:300px !important;
        height:200px !important;
    }
</style>

<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Store Name:</strong> {{ucwords($store->store_name)}} <br/> <strong>Active Current Balance:</strong> {{getNumberFormattedAmount($current_balance)}}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="fromStoreBalanceControl" action="{{route('admin.store-balance-control.store',$store->store_code)}}" enctype="multipart/form-data">
    @csrf
<div class="modal-body">
    <div class="form-group">
        <label class="control-label">Action Type</label>
            <select type="text" class="form-control input-sm" value=""  name="action_type" id="action_type" required autocomplete="off">
                <option value="" disabled selected>Select Type</option>
                <option value="deduction">Deduction</option>
                <option value="increment">Increment</option>
            </select>
    </div>

    <div class="form-group">
        <label class="control-label">Reasons</label>
            <select type="text" name="transaction_type" class="form-control input-sm" id="reasons" required autocomplete="off">
                <option value="" disabled selected>Select Reason</option>
            </select>
    </div>

    <div class="form-group">
        <label class="control-label">Amount</label> <small>Should be 2 digits or less after decimal</small>
        <input class="form-control input-sm" type="number" step="0.01"  name="transaction_amount" value="" id="amount" required>
    </div>

    <div id="transaction_code_div" style="display: none">
        <div class="form-group">
            <label class="control-label">Transaction Code</label>
            <input name="transaction_code" class="form-control input-sm" type="text" id="transaction_code">
        </div>
    </div>

    <div id="sales_reconciliation_attributes">
        <div id="order_code_div" style="display: none" class="form-group">
            <label class="control-label">Order Code</label>
            <input  class="form-control input-sm" type="text" name="order_code" value="" id="order_code">
        </div>
        <div id="ref_bill_no_div" style="display: none" class="form-group">
            <label class="control-label">Ref Bill No</label>
            <input  class="form-control input-sm" type="text" name="ref_bill_no" value="" id="ref_bill_no">
        </div>
    </div>

    <div class="form-group">
       <label class="control-label">Proof Of Document</label>
       <input type="file" name="proof_of_document" id="proof_of_document">
       <img id="image_preview" src="#" alt="your image" width="150px" height="100px" style="object-fit: cover;display: none" />
    </div>
    <div class="form-group">
        <label class="control-label">Remarks</label>
        <textarea class="form-control input-sm" name="remarks" id="remarks" required></textarea>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button id="saveStoreBalanceControl" type="submit" class="btn btn-primary">Save changes</button>
</div>
</form>
@include('Store::BalanceManagement.store-balance-control.scripts.add-balance-control-scripts')




