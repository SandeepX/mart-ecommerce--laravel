@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title.' Warehouse Link',
    'sub_title'=> "Create ".$title. ' Warehouses',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.warehouses.index'),
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
                                <a href="{{ route('admin.stores.warehouses.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{$title.' Warehouses'}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="createConnection" action="{{route($base_route.'.warehouses.sync')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Store</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2" name="store_code">
                                                <option selected value="" selected disabled>--Select An Option--</option>
                                                @if(isset($stores) && count($stores)> 0)
                                                    @foreach ($stores as $store)
                                                        <option value={{ $store->store_code }} {{ old('store_code') == $store->store_code ?  'selected' : ''}}>{{ $store->store_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Warehouse</label>
                                        <div class="col-sm-6">
                                            <select class="form-control select2" name="warehouse_codes[]" multiple>
                                                @if(isset($warehouses) && count($warehouses)> 0)
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->warehouse_code }}" {{ (collect(old('warehouse_codes'))->contains($warehouse->warehouse_code)) ? 'selected': '' }}>{{ $warehouse->warehouse_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary store_warehouse_connection">Save</button>
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
        $('#createConnection').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to create Store warehouse Connection  ?',
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

