<div class="form-group col-md-4">
    <label class="control-label  @error('luckydraw_name') text-red @enderror">

        LuckyDraw Name

        <span class="text-red">*</span></label>
    <div>
        <input type="text" class="form-control mx-1" value="{{isset($storeLuckydraw) ? $storeLuckydraw->luckydraw_name : old('luckydraw_name')  }}" placeholder=" Luckydraw Name" name="luckydraw_name"
               autocomplete="off">
        @error('luckydraw_name')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group col-md-4">
    <label class="control-label">Image</label>
    <div>
        <input type="file" class="form-control" name="image">
        @if(isset($storeLuckydraw))
        <img src="{{asset('uploads/prizes/images/'.$storeLuckydraw->image)}}" height="150" />
       @endif
        @error('image')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group col-md-4">
    <label class="control-label @error('eligibility_sales_amount') text-red @enderror">Eligibility Sales Amount<span class="text-red">*</span></label>
    <div>
        <input type="number" min="0" class="form-control" value="{{isset($storeLuckydraw) ? $storeLuckydraw->eligibility_sales_amount : old('eligibility_sales_amount')  }}" placeholder="Eligibility Sales Amount"
               name="eligibility_sales_amount"  autocomplete="off">
        @error('eligibility_sales_amount')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4">
    <label class="control-label @error('days') text-red @enderror">Days<span class="text-red">*</span></label>
    <div>
        <input type="number" min="0" max="1000" class="form-control" value="{{isset($storeLuckydraw) ? $storeLuckydraw->days : old('days')  }}" placeholder="Days"
               name="days" autocomplete="off">
        @error('days')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Opening Time</label>
    <div class="col-sm-6">
        <div class='input-group date datetimepicker'>
            <input type='text' class="form-control"
                   value="{{isset($storeLuckydraw->opening_time)? $storeLuckydraw->getOpeningTime():old('opening_time')}}"
                   name="opening_time"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>

</div>
<div class="form-group col-md-4">
    <label class="control-label @error('pickup_time') text-red @enderror">Pickup Time<span class="text-red">*</span></label>
    <div>
        <input type="number" min="0" max="1000" class="form-control" value="{{isset($storeLuckydraw) ? $storeLuckydraw->pickup_time : old('pickup_time')  }}" placeholder="Pickup Time"
               name="pickup_time" autocomplete="off">
        @error('pickup_time')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4">
    <label class="control-label @error('type') text-red @enderror">Type<span class="text-red">*</span></label>
    <div>
        <select required name="type" id="type" class="form-control">
            <option value="">
                Select One
            </option>
            <option value="cash" {{(isset($storeLuckydraw) && $storeLuckydraw->type === 'cash') ? 'selected':'' }}> cash </option>
            <option value="goods" {{(isset($storeLuckydraw) && $storeLuckydraw->type === 'goods') ? 'selected':'' }}> goods </option>

        </select>
        @error('type')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4" >
    <label class="control-label @error('prize') text-red @enderror">Prize<span class="text-red">*</span></label>
    <div>
        <input type="text" class="form-control" value="{{isset($storeLuckydraw) ? $storeLuckydraw->prize : old('prize')  }}" placeholder="Prize" name="prize"
                autocomplete="off">
        @error('prize')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-12">
    <label class="control-label">Remarks </label>
    <div>
        <textarea class="form-control summernote col-md-12" name="remarks">{{isset($storeLuckydraw) ? $storeLuckydraw->remarks : old('remarks')  }}</textarea>
        @error('remarks')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="row">
    <table class="table table-bordered" id="dynamicTable">
        <tr>
            <th>Terms</th>
        </tr>
        @if(isset($storeLuckydraw))
        @foreach(json_decode($storeLuckydraw->terms) as $term)
        <tr>
            <td><textarea class="form-control col-md-10" name="terms[]">{{$term}}</textarea></td>
            <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
        </tr>
        @endforeach
        @else
        <tr>
            <td><textarea class="form-control col-md-10" name="terms[]"></textarea></td>
            <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
        </tr>
        @endif
    </table>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
        </div>
    </div>
</div>
{{--<div class="form-group col-md-4">--}}
{{--    <label class="control-label">Status<span class="text-red">*</span></label>--}}
{{--    <div>--}}
{{--        <select name="status"  class="form-control">--}}
{{--            <option value="">--}}
{{--                All--}}
{{--            </option>--}}
{{--            <option value="open" {{(isset($storeLuckydraw) && $storeLuckydraw->status === 'open') ? 'selected':'' }}> Open </option>--}}
{{--            <option value="closed" {{(isset($storeLuckydraw) && $storeLuckydraw->status === 'closed') ? 'selected':'' }}> Closed </option>--}}

{{--        </select>--}}
{{--        @error('status')--}}
{{--        <small class="text-red">{{ $message }}</small>--}}
{{--        @enderror--}}
{{--    </div>--}}
{{--</div>--}}
