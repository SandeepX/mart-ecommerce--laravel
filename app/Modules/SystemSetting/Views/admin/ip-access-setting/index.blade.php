@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.ip-access-settings.index')}}" method="get">

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="ip_name">Ip Name</label>
                                        <input type="text" class="form-control" name="ip_name" id="ip_name" value="{{$filterParameters['ip_name']}}">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="ip_address">Ip Address</label>
                                        <input type="text" class="form-control" name="ip_address" id="ip_address" value="{{$filterParameters['ip_address']}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="allowed">Allowed</label>
                                        <select id="allowed" name="allowed" class="form-control">
                                            <option value="">
                                                All
                                            </option>
                                            <option value="1" {{1 == $filterParameters['allowed'] ?'selected' :''}}>
                                                Yes
                                            </option>
                                            <option value="0" {{0 == $filterParameters['allowed'] ?'selected' :''}}>
                                                No
                                            </option>

                                        </select>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{$title}}
                            </h3>

                            @can('Create Ip Access')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route($base_route.'create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{$title}}
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
                                    <th>Ip Name</th>
                                    <th>Ip Address</th>
                                    <th>Allowed</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($ipAddresses as $ipAddress)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$ipAddress->ip_name}}</td>
                                        <td>{{$ipAddress->ip_address}}</td>
                                        <td>
                                            <span class="label label-primary">
                                                {{$ipAddress->isAllowed() ? 'Yes' : 'No'}}
                                            </span>
                                        </td>
                                        <td>

                                            @can('Update Ip Access')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'edit', $ipAddress->ip_access_code),'Edit', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Ip Access')
                                                {!! \App\Modules\Application\Presenters\DataTable::createDeleteAction('Delete',route($base_route.'destroy',$ipAddress->ip_access_code),$ipAddress->ip_access_code,'Ip Address',$ipAddress->ip_access_code)!!}
                                            @endcan


                                        </td>
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
                            {{$ipAddresses->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection