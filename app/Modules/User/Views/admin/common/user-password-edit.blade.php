
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title text-center">
     Change Password Of {{$user->name}}
    </h4>
    @include("Admin::layout.partials.flash_message")
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form" id="updatePassword" action="{{route('admin.admin-password.update',$user->user_code)}}" method="post">
        {{csrf_field()}}
        @method('PUT')
        <div class="box-body">

            <div class="form-group">
                <label for="new-password" class="col-sm-4 control-label">New Password</label>
                <div class="col-sm-6">
                    <input id="new-password" type="password" class="form-control" placeholder="Enter new password" name="password" required>
                    @if ($errors->has('password')) <p style="color:red;">{{ $errors->first('password') }}</p> @endif <br>
                </div>
            </div>

            <div class="form-group">
                <label for="new-password" class="col-sm-4 control-label">{{ __('Confirm Password') }}</label>
                <div class="col-sm-6">
                    <input id="new-password" type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation')) <p style="color:red;">{{ $errors->first('password_confirmation') }}</p> @endif<br>
                </div>
            </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <button type="button" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary changePassword">Update</button>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button>--}}
</div>

    <script>
        $('.changePassword').click(function (e){
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to Change Password ?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#updatePassword').submit();
                } else if (result.isDenied) {
                    Swal.fire('Changes are not Saved !', '', 'info')
                }
            })
        })

    </script>

