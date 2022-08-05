@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
        [
        'page_title'=>$title,
        'sub_title'=> "Manage {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route('admin.static-page-images.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add New {{$title}}</h3>
                            @can('View Static Page images')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.static-page-images.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form"  id="createStaticPageImage" action="{{ route('admin.static-page-images.store') }}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}
                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Page Name</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2"   name="page_name" required autocomplete="off">
                                                <option  value ="store-finder">store finder</option>
                                                <option  value="allpasal-mart-register">Allpasal-mart-register </option>
                                                <option  value="allpasal-mini-mart-register">Allpasal-mini-mart-register</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Image</label>
                                        <div class="col-sm-6">
                                            <input type="file" class="form-control" name="image" required >
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary create">Add</button>
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
        $('#createStaticPageImage').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to add new static page image ?',
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

