<div class="form-group">
    <label class="col-sm-2 control-label">Image</label>
    <div class="col-sm-6">


        @if(isset($ourTeam->image))
            <img src="{{asset('uploads/contentManagement/our-team/'.$ourTeam->image)}}"
                 alt="Team Member Image" width="50" height="50">
        @endif
        <input type="file" class="form-control" {{old('image')  }}  name="image"   autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($ourTeam) ? $ourTeam->name : old('name')  }}" placeholder="Enter Name" name="name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Department</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($ourTeam) ? $ourTeam->department : old('department')  }}" placeholder="Enter Department" name="department" required autocomplete="off">

    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Delegation</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($ourTeam) ? $ourTeam->delegation : old('delegation')  }}" placeholder="Enter Delegation" name="delegation" required autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Message</label>
    <div class="col-sm-6">
        <textarea name="message" class="summernote" required id="" cols="90" rows="5"> {{ isset($ourTeam) ? $ourTeam->message : ''}} </textarea>
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($ourTeam) ? $ourTeam->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
