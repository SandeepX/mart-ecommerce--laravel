
<div class="panel panel-warning">

<div class="panel-heading">
    <h3 class="panel-title">
    <strong>
        {{isset($warehouseOrder) ? 'Edit the' : 'Add A'}} Warehouse Purchase Order
        {{isset($warehouseOrder) ? ': ' . $warehouseOrder->order_code : ' '}}
    </strong>    
    </h3>
</div>

<div class="panel-body">

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="col-md-12">
            <label>Warehouse</label>
            @if(!isset($warehouseOrder))
                <select style="width: 99%" class="select2"  name="warehouse_code" class="form-control" id="warehouse_code"  >
                    <option selected disabled value="" readonly="">--Select Warehouse--</option>
                    @if(isset($warehouses) && count($warehouses)>0)
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->warehouse_code }}">
                                {{$warehouse->warehouse_name }} ({{$warehouse->landmark_name }})
                            </option>
                        @endforeach
                    @endif
                </select>
            @else
                <select style="width: 99%" class="select2" name="warehouse_code" class="form-control" id="warehouse_code"  >
                    <option selected  value="{{ $warehouseOrder->warehouse->id }}">
                        {{$warehouseOrder->warehouse->full_name_nepali}}  {{$warehouseOrder->warehouse->reg_no}}
                    </option>
                </select>
            @endif
            </div>
            <div style="margin-top: 15px;" class="col-md-12">
            <label>Vendor</label>
            @if(!isset($warehouseOrder))
                <select style="width: 99%" class="select2"  name="vendor_code" class="form-control" id="vendor_code" >

                    @if(isset($vendors) && count($vendors)>0)
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendor_code }}">
                                {{$vendor->vendor_name }}
                            </option>
                        @endforeach
                    @endif
                        
                </select>
            @else
                <select style="width: 99%" class="select2" name="vendor_code" class="form-control" id="vendor_code"  >
                    <option selected  value="{{ $warehouseOrder->warehouse->id }}">
                        {{$warehouseOrder->warehouse->full_name_nepali}}  {{$warehouseOrder->warehouse->reg_no}}
                    </option>
                </select>
            @endif
            </div>
        </div>


        <div class="col-md-8">
            <div class="col-md-12">
            <label>Notes</label>
            <textarea id="invoice_remarks" rows="4"  class="form-control invoice_remarks"  name="invoice_remarks" > {!! isset($warehouseOrder) ? ltrim($warehouseOrder->invoice_remarks) : old('invoice_remarks') !!}</textarea>
             </div>
        </div>

    </div>
</div>

</div>




