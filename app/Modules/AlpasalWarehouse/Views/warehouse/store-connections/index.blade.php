@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route),
   ])
        @can('View WH Store Connection')


            <!-- Main content -->
            <section class="content">
                @include('AdminWarehouse::layout.partials.flash_message')
                <div class="row">

                    <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <form action="{{route('warehouse.store.connections')}}" method="get">
                                    <div class="col-xs-3">
                                        <label for="store_name">Store Name</label>
                                        <input type="text" class="form-control" name="store_name" id="store_name" value="{{$filterParameters['store_name']}}">
                                    </div>
                                    <div class="col-xs-3">
                                        <label for="store_owner">Store Owner Name</label>
                                        <input type="text" class="form-control"  name="store_owner_name" id="store_owner_name" value="{{$filterParameters['store_owner_name']}}">
                                    </div>
                                    <a href="{{route('warehouse.store.connections')}}" class="btn btn-danger btn-sm pull-right" style="margin-right:10px;margin-top: 10px;">Clear</a>
                                    <button type="submit" class="btn btn-primary btn-sm pull-right" style="margin-right:10px;margin-top: 10px;">Filter</button>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of {{  formatWords($title,true)}}
                                </h3>
                            </div>

                            <div class="box-body">

                                <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Store Name</th>
                                        <th>Store Owner</th>
                                        <th>Connection Status</th>
                                        <th>Connected Date </th>
                                        <th>Email</th>
                                        <th>Mobile No.</th>
                                        <th>Current Balance.</th>
    {{--                                    <th>Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($connectedStores as $i => $connectedStore)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td><a href="{{route('warehouse.store.connection-detail', $connectedStore->store_code)}}">{{$connectedStore->store_name}} ({{$connectedStore->store_code}})</a></td>
                                            <td>{{ $connectedStore->store_owner }}</td>
                                            <td>
                                                <b>{{$connectedStore->connection_status ? 'Active' : 'InActive'}}</b>
                                            </td>
                                            <td>{{$connectedStore->connected_date}}</td>
                                            <td>{{$connectedStore->connection_status ? $connectedStore->store_email : 'N/A'}}</td>

                                            <td>{{$connectedStore->connection_status ? $connectedStore->store_contact_mobile : 'N/A'}}</td>
                                            <td> <span class="label label-primary">{{$connectedStore->connection_status ? getNumberFormattedAmount($connectedStore->current_balance) : 'N/A'}}</span></td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <p class="text-center"><b>No records found!</b></p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                {{$connectedStores->appends($_GET)->links()}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        @endcan
    </div>
@endsection
