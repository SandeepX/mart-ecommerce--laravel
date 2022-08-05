<tr>
    <td colspan="12" class="hiddenRow">
        <div class="">
            <div class="accordian-body collapse" id="{{$mergedProduct->bill_merge_product_code}}">
                <form>
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover responsive nowrap bill_merge_table" style="width:100%;border-spacing: 0 0.85rem !important;">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="font-weight-bold mb-0">Order Type</p>
                                                <p class="mb-0">{{ucwords($mergedProduct->billMergeDetail->bill_type)}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="font-weight-bold mb-0">Bill Code</p>
                                                <p class="mb-0">{{$mergedProduct->billMergeDetail->bill_code}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="font-weight-bold mb-0">Initial Order Quantity:</p>
                                                <p class="mb-0">{{$mergedProduct->initial_order_quantity}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="align-items-center bill_merge_section">

                                                <p class="font-weight-bold mb-0">Quantity:</p>
                                                <p class="mb-0">
                                                    @if($masterBillMerge->status != 'dispatched' && $masterBillMerge->status != 'cancelled')
                                                        <input class="bill_merge_select" id="quantity{{$loop->index+1}}" name="quantity"  min="1" max="{{$mergedProduct->initial_order_quantity}}" type="number"  value="{{$mergedProduct->quantity}}">
                                                    @else
                                                       {{$mergedProduct->quantity}}
                                                    @endif
                                                </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="font-weight-bold mb-0">Current Stock:</p>
                                                <p class="mb-0">{{ ($mergedProduct->current_stock) ? $mergedProduct->current_stock  : 0 }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="font-weight-bold mb-0">Package Name:</p>
                                                <p class="mb-0">{{ $mergedProduct['ordered_package_name']  ??  $mergedProduct['package_name']}}
                                                    (Have {{$mergedProduct['package_micro_quantity']}} pcs.)</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="font-weight-bold mb-0">Status:</p>
                                                <p class="mb-0">
                                                    @if($masterBillMerge->status != 'dispatched' && $masterBillMerge->status != 'cancelled')
                                                        <select class="bill_merge_select" name="status" id="singleStatus{{$loop->index+1}}">
                                                            <option value="accepted" {{($mergedProduct->status == 'accepted') ? 'selected' : '' }}>Accepted</option>
                                                            <option value="rejected" {{($mergedProduct->status == 'rejected') ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                @else
                                                   {{ucwords($mergedProduct->status)}}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="align-items-center">
                                                <p class="font-weight-bold mb-0">
                                                    @if($masterBillMerge->status == 'pending')
                                                        @can('Update Bill Merge Product Detail')
                                                    <button class="btn btn-primary bill_merge_button" id="{{$loop->index+1}}"
                                                            href="{{route('warehouse.merge-bill.update.product-detail',[
                                                                           'billMergeDetailCode'=>$mergedProduct->bill_merge_details_code,
                                                                           'billMergeProductCode'=>$mergedProduct->bill_merge_product_code
                                                                   ])}}"
                                                            type="button">Update</button>
                                                        @endcan
                                                    @endif
                                                </p>
                                            </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </td>
</tr>

