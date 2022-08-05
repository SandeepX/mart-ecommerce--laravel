@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Warehouses
                            </h3>

                            @can('Create Warehouse')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.warehouses.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Warehouse
                                    </a>
                                </div>
                            @endcan

                        </div>

                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Location</th>
                                    <th>Landmark</th>
                                    <th>Warehouse Type</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warehouses as $i => $warehouse)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$warehouse->warehouse_name}}</td>
                                        <td>{{$warehouse->warehouse_code}}</td>
                                        <td>Warehouse name</td>
                                        {{-- <td>{{$warehouse->location->location_name}}</td>--}}
                                        <td>{{$warehouse->landmark_name}}</td>
                                        <td>{{ucwords($warehouse->warehouseType->warehouse_type_name)}}</td>
                                        <td>{{$warehouse->remarks}}</td>
                                        <td>


                                            @can('Show Warehouse')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Show ',route('admin.warehouses.show', $warehouse->warehouse_code),'Show warehouse', 'eye','info')!!}

                                            @endcan
                                            @can('Update Warehouse')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.warehouses.edit', $warehouse->warehouse_code),'Edit Warehouse', 'pencil','primary')!!}
                                            @endcan


{{--                                            @can('Delete Warehouse')--}}
{{--                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.warehouses.destroy',$warehouse->warehouse_code),$warehouse,'Warehouse',$warehouse->warehouse_name)!!}--}}
{{--                                            @endcan--}}
                                            @can('Change WH Admin Password AdminSide')
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('change Password ',route('admin.warehouse-password.edit', $warehouse->warehouse_code),'Update Warehouse Password', 'pencil','primary')!!}
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
