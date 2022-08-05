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
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Invoice Settings
                            </h3>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                @can('Create Invoice Setting')
                                    <a href="{{route('warehouse.warehouse-settings-invoice.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Invoice Settings
                                    </a>
                                @endcan
                            </div>
                        </div>

                        @can('View Invoice Setting Lists')
                            <div class="box-body">
                                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Setting Warehouse Invoice Code</th>
                                        <th>Fiscal Year</th>
                                        <th>Order Type</th>
                                        <th>Warehouse Code</th>
                                        <th>Starting Number</th>
                                        <th>Ending Number</th>
                                        <th>Next Number</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($invoicesettings as $invoicesetting)
                                    <tr>
                                        <td>{{$invoicesetting->id}}</td>
                                        <td>{{$invoicesetting->setting_warehouse_invoice_code}}</td>
                                        <td>{{$invoicesetting->fiscalyear->fiscal_year_name}}</td>
                                        <td>{{ ucwords(str_replace('_'," ",$invoicesetting->order_type))}}</td>
                                        <td>{{$invoicesetting->warehouse_code}}</td>
                                        <td>{{$invoicesetting->starting_number}}</td>
                                        <td>{{$invoicesetting->ending_number}}</td>
                                        <td>{{$invoicesetting->next_number}}</td>
                                        <td>
                                            @can('Update Invoice Setting')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('warehouse.warehouse-settings-invoice.edit', $invoicesetting->setting_warehouse_invoice_code),'Edit ', 'pencil','primary')!!}
                                            @endcan
                                            @can('Delete Invoice Setting')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('warehouse.warehouse-settings-invoice.destroy',$invoicesetting->setting_warehouse_invoice_code),$invoicesetting,'Invoice-Settings','')!!}
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
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
