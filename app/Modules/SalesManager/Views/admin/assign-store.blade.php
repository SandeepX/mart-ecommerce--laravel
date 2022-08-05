@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
           [
           'page_title'=>formatWords($title,true),
           'sub_title'=> "Manage ".formatWords($title,true),
           'icon'=>'home',
           'sub_icon'=>'',
           'manage_url'=>route($base_route.'.index'),
           ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->

                        <div class="box box-primary">
                            <div class="box-header with-border">

                                <h3 class="box-title">Assign Store To Manager</h3>

                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.salesmanager.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of SalesManager
                                    </a>
                                </div>

                            </div>

                            <!-- /.box-header -->
                            @include("Admin::layout.partials.flash_message")
                            @can('Assign Stores To Manager')
                                <div class="box-body">
                                    <form class="form-horizontal" method="post" role="form" id="assignStore" action="{{route('admin.salesmanager.assignStore.store')}}" >
                                    @csrf
                                    <div class="box-body">
                                        <input type="hidden" name="manager_code" id="managerCode" value="{{$managerCode}}" />

                                        <div class="form-group" >
                                            <label for="store" class="col-sm-2 control-label">Assign Store</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" multiple id="storeCode" name="store_code[]" required  >
                                                    @foreach($getAllStore as $key => $value)
                                                        <option  value="{{ $value->store_code }}">{{ ucfirst($value->store_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-success">save</button>
                                    </div>
                                </form>
                                </div>
                            @endcan
                        </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            @can('View All Assigned Store')
                <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List Of Store Assigned
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Manager Store Code</th>
                                    {{--                                            <th>Manager Name</th>--}}
                                    <th>Store Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($storeDetail as $key => $value)

                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$value->manager_store_code}}</td>
                                        {{--                                                <td>{{ucfirst($value->managerCode->name)}}</td>--}}
                                        <td>{{ucfirst($value->store->store_name)}}</td>
                                        <td>
                                            @can('Unlink Store From Manager')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete', route('admin.salesmanager.assignedStore.destroy',$value->manager_store_code),$value,'Assigned Store','SalesManager' )!!}
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

                        </div>
                    </div>
                </div>
            </div>
            @endcan

            <!-- /.row -->
        </section>

    </div>


@endsection


