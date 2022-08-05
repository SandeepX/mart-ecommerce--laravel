<div class="row">

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">User Name *</label>
            <input type="text" class="form-control" value="{{ isset($user) ? $user->name : old('name')  }}" placeholder="Enter User's Full Name"
                   name="name"  autocomplete="off">
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Login Email *</label>
            <input type="email" class="form-control" value="{{ isset($user) ? $user->login_email : old('login_email')  }}" placeholder="Enter Login Email"
                   name="login_email"  autocomplete="off">
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Login Phone *</label>
            <input type="number" class="form-control" value="{{ isset($user) ? $user->login_phone : old('login_phone')  }}" placeholder="Enter Login Phone"
                   name="login_phone"  autocomplete="off">
        </div>
    </div>

{{--    <div class="col-md-3 col-lg-4">--}}
{{--        <div class="form-group">--}}
{{--            <label for="role_id" class="control-label">Assign Role</label>--}}
{{--            <select id="role_id" name="role_id[]" class="form-control select2" multiple>--}}
{{--                @foreach($warehouseTypeRoles as $role)--}}
{{--                    <option value={{$role->id}}--}}
{{--                            {{isset($userRolesId) && in_array($role->id, $userRolesId) ? 'selected': ''}}--}}
{{--                            {{is_array(old('role_id')) && in_array($role->id, old('role_id')) ? 'selected': ''}}>--}}
{{--                        {{$role->name}}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}


</div>
