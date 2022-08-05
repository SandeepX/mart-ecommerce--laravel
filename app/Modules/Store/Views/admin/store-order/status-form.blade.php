    @can('Change Status Of Store Order')
    <div class="row">
        <div class="col-lg-3 col-md-3">
        <label class="control-label">Change Status</label>
            <div class="form-group">
                <select name="delivery_status" class="form-control select2" id="delivery_status" required>
                    <option value="all" selected disabled>--Select An Option--</option>
                    @foreach($storeOrderStatus as $status)

                        <option style="color:red" value="{{ $status }}" >{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
            <label class="control-label">Please Add Status Change Remarks (required)</label>
                <textarea style="height: 107.3px !important" required class="form-control summernote" name="remarks" cols="1"></textarea>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
        <label class="control-label"></label>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">Submit</button>
            </div>
        </div>
    </div>
    @endcan
