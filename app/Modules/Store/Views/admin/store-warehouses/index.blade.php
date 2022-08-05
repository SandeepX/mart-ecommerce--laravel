@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title.' Warehouses',
    'sub_title'=> "Manage ".$title.' Warehouses',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.warehouses.index'),
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

                           {{-- @can('Create Store Warehouse')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.stores.warehouses.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Store Warehouses
                                    </a>
                                </div>
                            @endcan--}}

                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store Name</th>
                                    <th>Warehouses</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stores as $i => $store)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$store->store_name}}</td>
                                        <td>
                                            @php
                                                $warehouses = $store->warehouses;
                                                $count = $store->warehouses->count();
                                                $more = ($count >3) ? ' & '.($count -3).' more Warehouses' : '';
                                            @endphp

                                            @foreach ($warehouses->take(3) as $warehouse)
                                                {{ $warehouse->warehouse_name}}
                                                @if(!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach

                                            @if($count >=3)
                                                {{ $more }}
                                            @endif
                                        </td>
                                        <td>
                                            @can('Update Store Warehouse')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.stores.warehouses.edit', $store->store_code),'Edit', 'pencil','primary')!!}
                                            @endcan

                                            @can('Show Store Warehouse')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores.warehouses.show', $store->store_code),'Show', 'eye','info')!!}
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

