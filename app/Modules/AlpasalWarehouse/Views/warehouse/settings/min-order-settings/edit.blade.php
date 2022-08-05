@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include("AdminWarehouse::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])
    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Editing Min Order Settings</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{route('warehouse.min-order-settings.index')}}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of Minimum Order Settings
                                    </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                            <div class="box-body">
                                <form class="form-horizontal" role="form" action="{{route('warehouse.min-order-settings.update',$minOrderSetting->warehouse_min_order_amount_setting_code)}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Minimum Amount For Normal Order(Store)</label>
                                                    <input class="form-control" type="number" name="min_order_amount" value="{{ $minOrderSetting->min_order_amount  }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Status</label>
                                                    <select class="form-control select2" type="text" name="status" required>
                                                        <option value="" {{$minOrderSetting->status == '' ? 'selected' : ''}}>Please Select</option>
                                                        <option value="1"{{$minOrderSetting->status == 1 ? 'selected' : ''}}>Active</option>
                                                        <option value="0" {{$minOrderSetting->status== 0 ? 'selected' : ''}}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                </form>
                            </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
