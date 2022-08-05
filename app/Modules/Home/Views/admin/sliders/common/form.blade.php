
<div class="form-group">
    <label  class="col-sm-2 control-label">
      Slider Image
     </label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="slider_image" {{ !isset($slider) ? 'required' : ''  }} >
        @if(isset($slider))
        <br>
        <div class="col-sm-6">
        <img src="{{asset($slider->uploadFolder.$slider->slider_image)}}" alt="Slider Img" width="100%" height="100%">
        </div>
        @endif
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label"> Slider Url</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($slider) ? $slider->slider_url : old('slider_url')  }}" placeholder="Enter the Slider Url" name="slider_url" autocomplete="off">
    </div>
</div>