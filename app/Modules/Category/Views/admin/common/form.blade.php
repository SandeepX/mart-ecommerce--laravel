<div class="form-group">
    <label  class="col-sm-3 control-label">Category Type</label>
    <div class="col-sm-6">
        <select class="form-control select2" name="category_type_code[]" id="category_type" multiple>
            @foreach ($categoryTypes as $categoryType)
                <option value={{ $categoryType->category_type_code }} {{ (collect(old('category_type_code'))->contains($categoryType->category_type_code)) ? 'selected': '' }} {{ isset($categoryTypeCodes) && in_array($categoryType->category_type_code, $categoryTypeCodes) ? 'selected': '' }}  >{{ $categoryType->category_type_name }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="form-group" id="select-category">
    <label  class="col-sm-3 control-label">Upper Category</label>
    <div class="col-sm-6">
        <select style="width: 520px" class="select2 form-control" id="upper_category" name="upper_category_code">
            <option value="" >Root</option>
            @foreach ($categories as $cate)
                <option value={{ $cate->category_code }} {{(isset($category) && $category->upper_category_code == $cate->category_code) || old('upper_category_code') == $cate->category_code ? 'selected' : '' }}>{{ $cate->path }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Category Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($category) ? $category->category_name : old('category_name')  }}" placeholder="Enter the Category Name" name="category_name" required autocomplete="off">
    </div>
</div>




<div class="form-group">
    <label  class="col-sm-3 control-label">
      Category Banner <br>
       <small>[Dimension : (width x height) : 1125 x 240]</small>
     </label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="category_banner" >
        @if(isset($category) && isset($category->category_banner))
        <br>
        <div class="col-sm-6">
        <img src="{{asset('uploads/categories/banners/'.$category->category_banner)}}" alt="{{$category->category_banner}}" width="100%" height="100%">
        </div>
        @endif
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label">
        Category Image <br>
        <small>[Dimension : (width x height) : 85 x 70]</small>
    </label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="category_image" >
        @if(isset($category) && isset($category->category_image))
            <br>
            <div class="col-sm-6">
                <img src="{{asset('uploads/categories/images/'.$category->category_image)}}" alt="{{$category->category_image}}" width="100px" height="100px">
            </div>
        @endif
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label">
        Category Icon <br>
        <small>[Dimension : (width x height) : 40 x 40]</small>
    </label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="category_icon" >
        @if(isset($category) && isset($category->category_icon))
            <br>
            <div class="col-sm-6">
                <img src="{{asset('uploads/categories/icons/'.$category->category_icon)}}" alt="{{$category->category_icon}}" width="50px" height="50px">
            </div>
        @endif
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-3 control-label">Category Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($category) ? $category->remarks : old('remarks')  }}" placeholder="Enter the Category Remarks" name="remarks" autocomplete="off">
    </div>
</div>
