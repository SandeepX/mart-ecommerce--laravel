<div class="alert alert-success alert-block" id="message_block" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong></strong>
</div>

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">

                <!-- /.box-header -->
                @include("Admin::layout.partials.flash_message")
                <div class="box-body">
                    <form class="form-horizontal" id="changeWarehousePassword" role="form" action="{{route('admin.warehouse-password.update',$warehouseUser->user_code)}}" method="post">
                        {{csrf_field()}}
                        @method('PUT')
                        <div class="box-body">
                            <div class="form-group">
                                <label for="new-password" class="col-sm-2 control-label">New Password</label>
                                <div class="col-sm-6">
                                    <input id="new-password" type="password" class="form-control" placeholder="Enter new password" name="password" required>
{{--                                    @if ($errors->has('password')) <p style="color:red;">{{ $errors->first('password') }}</p> @endif <br>--}}
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new-password" class="col-sm-2 control-label">{{ __('Confirm Password') }}</label>
                                <div class="col-sm-6">
                                    <input id="new-password" type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" required>
{{--                                    @if ($errors->has('password_confirmation')) <p style="color:red;">{{ $errors->first('password_confirmation') }}</p> @endif<br>--}}
                                    <span class="text-danger error-text password_confirmation_error"></span>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary changeWarehousePasswordBtn" id="changeWarehousePasswordBtn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->

    </div>
    <!-- /.row -->
</section>
