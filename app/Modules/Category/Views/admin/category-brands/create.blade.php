@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title.' Brand Link',
    'sub_title'=> "Create ".$title. ' Brands',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.brands.index'),
    ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add Category Brands </h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.categories.brands.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{$title.' Brands'}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form"id="createCategoryBrand" action="{{route($base_route.'.brands.sync')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Root Category</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2" name="category_code">
                                                <option selected value="" selected disabled>--Select An Option--</option>
                                                @if(isset($categories) && count($categories)> 0)
                                                    @foreach ($categories as $category)
                                                        <option value={{ $category->category_code }} {{ old('category_code') == $category->category_code ?  'selected' : ''}}>{{ $category->category_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Brands</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2" name="brand_codes[]" multiple>
                                                @if(isset($brands) && count($brands)> 0)
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->brand_code }}" {{ (collect(old('brand_codes'))->contains($brand->brand_code)) ? 'selected': '' }}>{{ $brand->brand_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary addCategoryBrand">Save</button>
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
        $('#createCategoryBrand').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to store category brand detail  ?',
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




