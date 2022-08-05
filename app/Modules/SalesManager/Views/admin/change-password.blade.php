@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
           [
           'page_title'=>formatWords($title,true),
           'sub_title'=> "Change Password ".formatWords($title,true),
           'icon'=>'home',
           'sub_icon'=>'',
           'manage_url'=>route($base_route.'.index'),
           ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" action="{{route('admin.salesManager.updateSalesManagerPassword',$managerUserCode)}}" method="post">
                                {{csrf_field()}}
                                @method('PUT')
                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="new-password" class="col-sm-2 control-label">New Password</label>
                                        <div class="col-sm-6">
                                            <input id="new-password" type="password" class="form-control" placeholder="Enter new password" name="password" required>
                                            @if ($errors->has('password')) <p style="color:red;">{{ $errors->first('password') }}</p> @endif <br>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="new-password" class="col-sm-2 control-label">{{ __('Confirm Password') }}</label>
                                        <div class="col-sm-6">
                                            <input id="new-password" type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" required>
                                            @if ($errors->has('password_confirmation')) <p style="color:red;">{{ $errors->first('password_confirmation') }}</p> @endif<br>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>



        </section>

    </div>


@endsection



