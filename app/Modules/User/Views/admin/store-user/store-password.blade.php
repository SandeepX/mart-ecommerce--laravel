@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Update {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route('admin.stores.index'),
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
                            <form class="form-horizontal" role="form" id="changeStorePassword" action="{{route('admin.store-password.update',$storeAdmin->user_code)}}" method="post">
                                {{csrf_field()}}
                                @method('PUT')
                                <div class="box-body">


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">User: {{$storeAdmin->name}}</label>
                                    </div>

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
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary updateStorePassword">Update</button>
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

    </div>


@endsection

@push('scripts')

    <script>
        $('#changeStorePassword').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to Change password ?',
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


