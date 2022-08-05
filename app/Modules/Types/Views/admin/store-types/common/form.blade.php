<div class="form-group">
    <label class="col-sm-2 control-label  @error('store_type_name') text-red @enderror">
        Store Type Name
        <span class="text-red">*</span></label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($storeType) ? $storeType->store_type_name : old('store_type_name')  }}" placeholder="Enter the Store Type Name" name="store_type_name" required autocomplete="off">
        @error('store_type_name')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label  @error('image') text-red @enderror">
        Image

        <span class="text-red">*</span></label>
    <div class="col-sm-6">
        <input type="file" class="form-control"  name="image" >
        <small class="text-red">[Dimension : (width x height) : 350 x 200]</small>
        @error('image')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
    <br/>

    @if(isset($storeType->image))
    <div class="col-sm-6">
        <label style="margin-left: 48px;">Current Image</label>    <img src="{{asset('uploads/storetypes/images/'.$storeType->image)}}" alt="{{$storeType->store_type_name}}" width="50px" height="50px" style="margin-left: 40px;margin-top: 10px;">
    </div>
    @endif
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
        <textarea class="form-control summernote" name="description">{{isset($storeType) ? $storeType->description : old('description')  }}</textarea>
    </div>
</div>
