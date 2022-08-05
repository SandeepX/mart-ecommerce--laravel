@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title.' Invoice',
    'sub_title'=> "Manage {$title} Invoice",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route).'/invoice',
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

                            <h3 class="box-title">Add New Invoice Settings</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                @can('View Invoice Setting Lists')
                                    <a href="{{route('warehouse.warehouse-settings-invoice.index')}}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of Invoice Settings
                                    </a>
                                @endcan
                            </div>

                        </div>

                        @can('Create Invoice Setting')
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form class="form-horizontal" role="form" action="{{route('warehouse.warehouse-settings-invoice.store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Order Type</label>
                                                    <select class="form-control select2" type="text" name="order_type" required>
                                                        <option value="" selected>Please Select</option>
                                                        <option value="store_order" @if(old('order_type') == 'store_order') selected @endif>Store Order</option>
                                                        <option value="store_pre_order" @if(old('order_type') == 'store_pre_order') selected @endif>Store Pre Order</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Invoice Starting Number</label>
                                                    <input class="form-control" type="number" name="starting_number" value="{{old('starting_number')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Invoice Ending Number</label>
                                                    <input class="form-control" type="number" name="ending_number" value="{{old('ending_number')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                             <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                </form>
                            </div>
                        @endcan
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
