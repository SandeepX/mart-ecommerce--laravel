{{--<div class="form-group">--}}
    {{--<label class="col-sm-2 control-label">User Type</label>--}}
    {{--<div class="col-sm-6">--}}
        {{--@foreach(config('user_types') as $key =>$user_type)--}}
        {{--<select name="user_type" class="form-control">--}}
            {{--<option value="{{$key}}">{{$user_type}}</option>--}}
        {{--</select>--}}
        {{--@endforeach--}}
        {{--<input type="text" class="form-control" value="{{isset($user) ? $user->name : old('name')  }}" placeholder="Enter User's Full Name" name="name" required autocomplete="off">--}}
    {{--</div>--}}
{{--</div>--}}

<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($user) ? $user->name : old('name')  }}" placeholder="Enter User's Full Name" name="name" required autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Login Email</label>
    <div class="col-sm-6">
        <input type="email" class="form-control" value="{{ isset($user) ? $user->login_email : old('login_email')  }}" placeholder="Enter Login Email" name="login_email" required autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Login Phone</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" value="{{ isset($user) ? $user->login_phone : old('login_phone')  }}" placeholder="Enter Login Phone" name="login_phone" required autocomplete="off">
    </div>
</div>


@if(!isset($user))
<div class="form-group">
    <label  class="col-sm-2 control-label">Password</label>
    <div class="col-sm-6">
        <input type="password" class="form-control" value="" placeholder="Enter Login Password" name="password" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Confirm Password</label>
    <div class="col-sm-6">
        <input type="password" class="form-control" value="" placeholder="Confirm Password" name="password_confirmation" required autocomplete="off">
    </div>
</div>

@endif

<div class="form-group">
    <label for="role_id" class="col-sm-2 control-label">Assign Role</label>
    <div class="col-sm-6">
        <select id="role_id" name="role_id[]" class="form-control select2" multiple>
            @foreach($adminTypeRoles as $role)
                <option value={{$role->id}}
                        {{isset($userRolesId) && in_array($role->id, $userRolesId) ? 'selected': ''}}
                        {{is_array(old('role_id')) && in_array($role->id, old('role_id')) ? 'selected': ''}}>
                    {{$role->name}}
                </option>
            @endforeach
        </select>
    </div>
</div>

{{--<div class="form-group">--}}
    {{--<label  class="col-sm-2 control-label">Remarks</label>--}}
    {{--<div class="col-sm-6">--}}
        {{--<input type="text" class="form-control" value="{{ isset($user) ? $user->remarks : old('remarks')  }}" placeholder="Enter  Remarks" name="remarks" autocomplete="off">--}}
    {{--</div>--}}
{{--</div>--}}