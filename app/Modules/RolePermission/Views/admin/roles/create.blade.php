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

                            <h3 class="box-title">Add A {{$title}}</h3>

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
                            <form class="form-horizontal" id="addNewRole" role="form" action="{{route($base_route.'store')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="container-fluid">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"  >Name</label>
                                            <input type="text" class="form-control" name="name" value="{{old('name') }}" placeholder="Enter role name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="for_user_type" class="control-label"  >For User Type</label>
                                            <select id="for_user_type" name="for_user_type" class="form-control">
                                                <option value="">Select User Types</option>
                                                @foreach($roleUserTypes as $key=>$userType)
                                                    <option value={{$userType}}
                                                            {{old('for_user_type') == $userType ? 'selected' : ''}}>
                                                        {{$key}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" >Description</label>
                                            <textarea id="description" class="form-control summernote" name="description" placeholder="Enter role description">{{old('description')}}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        @include("RolePermission::admin.roles.permission-list")
                                    </div>
                                </div>


                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary addRole">Add</button>
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
    $('#addNewRole').submit(function (e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }


        Swal.fire({
            title: 'Add new role permission ?',
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
