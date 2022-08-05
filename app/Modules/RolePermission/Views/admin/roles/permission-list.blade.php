<div class="form-group" id="all_permissions">
    <label for="check_all">Check All Permissions</label>&nbsp;&nbsp;
    <input style="position:absolute;" id="check_all" type="checkbox">

    <div style="margin-top: 0px;">

        <div class="row">
            @foreach ($permissionGroups as $permissionGroup => $permissions)

                @php
                    $permissionGroupReplaced = str_replace(" ","_",$permissionGroup);
                @endphp

                <div class="col-md-4" style="margin-top: 25px;">
                    <label for="{{$permissionGroupReplaced }}">{{ucwords($permissionGroup)}}</label>
                    <input style="position:absolute;margin-left: 5px;" id="{{ $permissionGroupReplaced }}" name="group_heads[]" type="checkbox" class="group_head all-permissions"
                           value="{{$permissionGroup}}" {{ is_array(old('group_heads')) && in_array($permissionGroup, old('group_heads')) ? 'checked' : '' }}>


                    <ul class="list-unstyled" style="overflow-y: scroll; height:100px;margin-top: 10px;">
                        @foreach ($permissions as $permission)

                            @php
                                $permissionNameReplaced = str_replace(" ","_",$permission->name);
                            @endphp

                            <li>
                                <input id="{{$permissionNameReplaced}}" name="permission_id[]" value="{{ $permission->id }}" type="checkbox"  class=" {{ $permissionGroupReplaced }} all-permissions"
                                    {{ is_array(old('permission_id')) && in_array($permission->id, old('permission_id')) ? 'checked' : '' }}>

                                <label for="{{$permissionNameReplaced}}" class="icheck-label form-label" style="margin-bottom: 0px;font-weight: 600">{{ $permission->name}}</label>
                            </li>

                        @endforeach

                    </ul>
                </div>
            @endforeach
        </div>

    </div>
</div>

