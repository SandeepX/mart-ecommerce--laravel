@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage ".$title.' Warehouses',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Warehouses Connection with Store : {{$store->store_name}} / {{$store->store_code}}
                            </h3>

                            @can('Update Store Warehouse')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.stores.warehouses.edit', $store->store_code) }}"
                                       style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Update Store Warehouses
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
                                    <th>Warehouse Name</th>
                                    <th>Connection Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($storeWarehouses as $i => $warehouse)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$warehouse->warehouse_name}}</td>
                                        <td>
                                            {{-- <p>status: {{$warehouse->pivot->connection_status}}</p>--}}
                                            @if($warehouse->pivot['connection_status'])
                                                @php
                                                    $activeStatus = 'Deactivate';
                                                @endphp
                                                <span class="label label-success">On</span>
                                            @else
                                                @php
                                                    $activeStatus = 'Activate';
                                                @endphp
                                                <span class="label label-danger">Off</span>
                                            @endif

                                        </td>
                                        <td>

                                            @can('Update Store Warehouse')
                                                @if($warehouse->isOpenWarehouseType())
                                                    <span class="label label-danger">Open Warehouse Type</span>
                                                @else
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,
                                            route('admin.stores.warehouses.toggle-connection', ['store'=>$store->store_code,'warehouse'=>$warehouse->warehouse_code,]),
                                            'Edit', 'pencil','primary')!!}
                                                @endif

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

