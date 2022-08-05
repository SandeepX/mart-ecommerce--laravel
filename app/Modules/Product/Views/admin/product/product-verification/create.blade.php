
<form method="post" id="productVerify" action="{{route('admin.product-verification.store',['product'=> $product->product_code])}}">
    @csrf
<div id="{{$targetModalID}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Verify {{$product->product_name}}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Verification Status</label>
                        <select class="select2" name="verification_status" required >
                            <option value="" selected disabled>--Select An Option--</option>
                            <option value="approved" >Approved</option>
                            <option value="rejected" >Rejected</option>
                            <option value="on hold" >On Hold</option>
                        </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Remarks</label>
                    <input type="text" class="form-control" placeholder="Enter the Remarks" name="remarks" required autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>

    </div>
</div>
</form>

@push('scripts')
    <script>
        $('#productVerify').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'product verification status change ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });
    </script>
@endpush
