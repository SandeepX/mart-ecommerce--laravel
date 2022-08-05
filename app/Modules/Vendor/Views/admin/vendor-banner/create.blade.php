@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "Create {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.create', $vendor->slug),
    ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add A {{$title}}</h3>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="vendorBannerCreate" action="{{route($base_route.'.store', $vendor->slug)}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">
                                        <tr>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Banner</label>
                                                <div class="col-sm-6">
                                                    <table class="table" id="dynamic_field">
                                                        <tr>
                                                            <td>
                                                                <input type="file" class="form-control" name="banners[]" required >
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <button type="button" id="add_more" class="btn btn-success">Add More</button>
                                                </div>
                                            </div>
                                        </tr>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary vendorBannerAdd">Add</button>
                                </div>
                            </form>
                        </div>

                        @if(isset($vendorBanners) && count($vendorBanners)>0)

                        <table id="{{ $base_route }}-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Banner</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($vendorBanners as $i => $banner)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>
                                            <img src="{{asset('uploads/vendor/banners/'.$banner->banner)}}" alt="banner not found" width="50" height="50">
                                        </td>
                                        <td>
                                            @if($banner->active === 1)
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Deactivate ',route('admin.vendors.banners.change-status', [$vendor->slug, $banner->banner]),'Deactivate', 'times','danger')!!}

                                            @else
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Activate ',route('admin.vendors.banners.change-status', [$vendor->slug, $banner->banner]),'Activate', 'check','success')!!}

                                            @endif
                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.vendors.banners.destroy', [$vendor->slug, $banner->banner]),$banner,'Banner',$banner->banner)!!}
                                        </td>

                                    </tr>
                                    @endforeach
                            </tbody>

                        </table>

                        @endif
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
    @includeIf('Vendor::admin.vendor-banner.script');

    <script>
        $('#vendorBannerCreate').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to create vendor banner?',
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
