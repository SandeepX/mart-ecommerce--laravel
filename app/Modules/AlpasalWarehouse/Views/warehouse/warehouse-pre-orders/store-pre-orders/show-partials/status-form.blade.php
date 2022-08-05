<div class="row">
    <div class="col-sm-12">
        <form id="preOrderStatusForm" method="post" action="{{route('warehouse.warehouse-pre-orders.store-orders.update-status',['storePreOrder'=>$storePreOrder->store_preorder_code])}}">
            @csrf
{{--            @include(''.$module.'.warehouse.warehouse-pre-orders.store-pre-orders.show-partials.dispatch_details')--}}
            <div class="row" id="wh_store_pre_order_status_form">

                <div class="col-lg-3 col-md-3">
                    <label for="status" class="control-label">Change Status</label>
                    <div class="form-group">
                        <select name="status" class="form-control select2" id="status" required>
                            <option value=" " selected disabled>--Select An Option--</option>
                            @foreach($storePreOrderStatus as $status)
                                <option value="{{$status}}" {{old('status') == $status ? 'selected' : ''}}>
                                    @if($status == 'cancelled')
                                        Cancel
                                    @elseif($status == 'processing')
                                        Processing
                                    @elseif($status == 'ready_to_dispatch')
                                        Ready To Dispatch
{{--                                 @elseif($status == 'dispatched')--}}
{{--                                        Dispatch--}}
                                    @endif


                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="control-label">Please Add Status Change Remarks (required)</label>
                        <textarea id="remarks" style="height: 107.3px !important" required class="form-control summernote" name="remarks" cols="1">{{old('remarks')}}</textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <label class="control-label"></label>
                    <div class="form-group">
                        <button id="order_status_submit" type="submit" class="btn btn-block btn-primary">Submit</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
