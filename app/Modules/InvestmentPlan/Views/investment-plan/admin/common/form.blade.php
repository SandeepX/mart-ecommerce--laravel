<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="name"  required placeholder=" Investment plan name" value="{{isset($investmentDetail) ? $investmentDetail->name : '' }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Investment Type</label>
    <div class="col-sm-6">
        <select class="form-control select2" required name="ip_type_code"  autocomplete="off">
            <option value="">Select Investment Plan Type</option>
            @if(count($getAllIPTypes)>0)
                @foreach($getAllIPTypes as $key => $type)
                    <option {{ (isset($investmentDetail) && ($investmentDetail->ip_type_code == $type['ip_type_code'])) ? 'selected' : '' }} value="{{ $type['ip_type_code'] }}">{{ $type['name'] }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">Paid Up Capital(Rs.)</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" name="paid_up_capital"  placeholder="Paid Up Capital" value="{{isset($investmentDetail) ? $investmentDetail->paid_up_capital :'' }}"  />
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">Per Unit Share Price(Rs.)</label>
    <div class="col-sm-6">
        <input type="number"  step="any"  class="form-control" name="per_unit_share_price"    placeholder=" Per Unit Share Price" value="{{isset($investmentDetail) ? $investmentDetail->per_unit_share_price :'' }}"  />
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">Maturity Period(in months)</label>
    <div class="col-sm-6">
        <input type="number" min="1" class="form-control" name="maturity_period"  required  placeholder="maturity period in months(e.g 18 months)" value="{{isset($investmentDetail) ? $investmentDetail->maturity_period : '' }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Target capital(Rs.)</label>
    <div class="col-sm-6">
        <input type="number"  step="any" min="1" class="form-control" name="target_capital"  required  placeholder=" Target Capital" value="{{isset($investmentDetail) ? $investmentDetail->target_capital :'' }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Price Start Range(Rs.)</label>
    <div class="col-sm-6">
        <input type="number"   step="any" min="1" class="form-control" name="price_start_range"  required placeholder=" Price Start Range" value="{{isset($investmentDetail) ? $investmentDetail->price_start_range : '' }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Price End Range(Rs.)</label>
    <div class="col-sm-6">
        <input type="number" step="any" min="1" class="form-control" name="price_end_range"  required placeholder=" Price End Range" value="{{isset($investmentDetail) ? $investmentDetail->price_end_range : '' }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Interest Rate(%)</label>
    <div class="col-sm-6">
        <input type="number" step="any" min="1" class="form-control" name="interest_rate"  required placeholder="Interest rate" value="{{isset($investmentDetail) ? $investmentDetail->interest_rate : '' }}"  />
    </div>
</div>



<div class="form-group">
    <label class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
        <textarea id="description" class="form-control summernote" name="description"  autocomplete="off"  value="{{isset($investmentDetail) ? $investmentDetail->description : ''  }}"  >{{isset($investmentDetail->description)? $investmentDetail->description: ''}}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Terms</label>
    <div class="col-sm-6">
        <textarea id="terms" class="form-control summernote" name="terms"  autocomplete="off"  value="{{isset($investmentDetail) ? $investmentDetail->terms : ''  }}"  >{{isset($investmentDetail->terms)? $investmentDetail->terms: ''}}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Image</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="image" {{ !isset($investmentDetail) ? 'required' : ''  }} >
        @if(isset($investmentDetail['image']) && !empty(($investmentDetail['image'])))
            <img src="{{asset('uploads/investment/images/'.$investmentDetail['image'])}}"
                 alt="" width="150"
                 height="150">
        @endif
    </div>
</div>


<div class="form-group ">
    <label  class="col-sm-2 control-label">Is_active</label>
    <div class="col-sm-6">
        @if(isset($investmentDetail))
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active" {{($investmentDetail->is_active==1)? 'checked': '' }} />
        @else
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"  />
        @endif
    </div>
</div>

{{--<div class="form-group">--}}
{{--    <label class="col-sm-2 control-label">Sort Order</label>--}}
{{--    <div class="col-sm-6">--}}
{{--        <input type="number" step="any" min="0" class="form-control" name="sort_order"  required value="{{isset($investmentDetail) ? $investmentDetail->sort_order : '' }}"  />--}}
{{--    </div>--}}
{{--</div>--}}










