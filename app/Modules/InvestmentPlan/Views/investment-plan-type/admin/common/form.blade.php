<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="name"  required placeholder=" Investment Type name" value="{{isset($investmentTypeDetail) ? $investmentTypeDetail->name : '' }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
        <textarea id="description" class="form-control summernote" name="description"  autocomplete="off"  value="{{isset($investmentTypeDetail) ? $investmentTypeDetail->description : ''  }}"  >{{isset($investmentTypeDetail->description)? $investmentTypeDetail->description: ''}}</textarea>
    </div>
</div>

<div class="form-group ">
    <label  class="col-sm-2 control-label">Is_active</label>
    <div class="col-sm-6">
        @if(isset($investmentTypeDetail))
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active" {{($investmentTypeDetail->is_active==1)? 'checked': '' }} />
        @else
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"  />
        @endif
    </div>
</div>











