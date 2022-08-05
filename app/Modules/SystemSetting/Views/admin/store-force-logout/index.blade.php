@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">{{$title}}</h3>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="forceLogout" action="{{route('admin.store-user-force-logout')}}" method="post">
                                @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">select store to logout Associated user</label>
                                        <div class="col-sm-6">
                                            <select class="select2" multiple required value="" name="storeCode[]"  id="storeCode" >
                                                @foreach($getAllStores as $value)
                                                    <option value="{{$value->store_code}}">{{$value->store_name}}({{$value->store_code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="box-footer">
                                    <button  type="submit" style="width: 49%;margin-left: 17%;" class=" btn btn-block btn-primary forceLogout ">submit</button>
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
        $('#forceLogout').submit(function (e, params) {

            var localParams = params || {};
            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to Force logout selected store users  ?',
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

