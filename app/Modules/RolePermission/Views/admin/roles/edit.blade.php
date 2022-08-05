@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Edit A {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{$title}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="editRole" action="{{route($base_route.'update',$role->id)}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="_method" value="PUT">

                                <div class="container-fluid">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="{{$role->name }}" placeholder="Enter role name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">For User Type</label>
                                            <input type="text" class="form-control" readonly name="for_user_type" value="{{$role->for_user_type }}">
{{--                                            <label for="for_user_type" class="control-label"  >For User Type</label>--}}
{{--                                            <select id="for_user_type" name="for_user_type" class="form-control">--}}
{{--                                                @foreach($roleUserTypes as $key=>$userType)--}}
{{--                                                    <option value={{$userType}}--}}
{{--                                                            {{$role->for_user_type == $userType ? 'selected' : ''}}>--}}
{{--                                                        {{$key}}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" >Description</label>
                                            <textarea id="description" class="form-control summernote" name="description"
                                                      placeholder="Enter role description">
                                                {{$role->description}}
                                            </textarea>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
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
                                                            <input style="position:absolute;" id="{{ $permissionGroupReplaced }}" name="group_heads[]" type="checkbox" class="group_head all-permissions"
                                                                   value="{{$permissionGroup}}" {{ count($rolePermissionsId) == count($permissions) ? 'checked' : '' }}>


                                                            <ul class="list-unstyled" style="overflow-y: scroll; height:100px;margin-top: 10px;">
                                                                @foreach ($permissions as $permission)
                                                                    @php
                                                                        $permissionNameReplaced = str_replace(" ","_",$permission->name);
                                                                    @endphp

                                                                    <li>
                                                                        <input id="{{$permissionNameReplaced}}" name="permission_id[]" value="{{ $permission->id }}" type="checkbox"  class=" {{ $permissionGroupReplaced }} all-permissions"
                                                                                {{ in_array($permission->id, $rolePermissionsId) || (is_array(old('permission_id')) && in_array($permission->id, old('permission_id'))) ? 'checked' : '' }} >

                                                                        <label for="{{$permissionNameReplaced}}" class="icheck-label form-label" style="margin-bottom: 0px;font-weight: 600">{{ $permission->name}}</label>
                                                                    </li>

                                                                @endforeach

                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary updateRole">Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection

@push('scripts')
@include("RolePermission::admin.roles.role-script")


<script>
    $('#editRole').submit(function (e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }


        Swal.fire({
            title: 'Edit role/permission  ?',
            showCancelButton: true,
            confirmButtonText: `Yes`,
            padding:'10em',
            width:'500px'
        }).then((result) => {
            if (result.isConfirmed) {

                $(e.currentTarget).trigger(e.type, { 'send': true });
                Swal.fire({
                    title: 'Please wait...',
                    hideClass: {
                        popup: ''
                    }
                })
            }
        })
    });
</script>

@endpush
