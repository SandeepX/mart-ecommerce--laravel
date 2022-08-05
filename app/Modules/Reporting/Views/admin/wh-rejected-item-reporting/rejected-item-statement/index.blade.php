@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
        [
       'page_title'=> formatWords($title,false),
       'sub_title'=>'Manage '. formatWords($title,false),
       'icon'=>'home',
       'sub_icon'=>'',
       'manage_url'=> route($base_route.'index'),
       ])
        <style>
            .pagination {
                width: 100% !important;
            }

        </style>
        <!-- Main content -->
        <section class="content">
            <div class="row">

                @include(''.$module.'admin.wh-rejected-item-reporting.rejected-item-statement.filter-form')

                <div class="col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Last Sync Date
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                Last Sync Normal Order Rejected Item Date : {{$lastRejectedItemSyncData['normalOrder']['date']}}<br/>
                                Status :  {{$lastRejectedItemSyncData['normalOrder']['status']}}
                                Order Count :  {{$lastRejectedItemSyncData['normalOrder']['count']}}
                            </div>
                            <div class="col-md-6">
                                Last Sync PreOrder Rejected Item Date : {{$lastRejectedItemSyncData['preOrder']['date']}} <br/>
                                Status :  {{$lastRejectedItemSyncData['preOrder']['status']}}
                                Order Count :  {{$lastRejectedItemSyncData['preOrder']['count']}}
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                               Rejected Item Statement
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.wh-rejected-item-reporting.index')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Back
                                </a>
                            </div>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.wh-rejected-item-daybook-excel-export')}}" style="border-radius: 0px; " class="btn btn-sm btn-success excel-export-daybook">
                                    <i class="fa fa-file"></i>
                                    Excel Export
                                </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Store Name</th>
                                    <th>Order Date</th>
                                    <th>Order Type</th>
                                    <th>Quantity</th>
                                    <th>Unit Rate</th>
                                    <th>Order Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($rejectedItemStatement as $key =>$statement)
                                    <tr>
                                        <td>{{++$loop->index}}</td>
                                        <td>
                                            <strong> Product Name:</strong> {{$statement->product_name}}
                                            @if($statement->product_variant_code)
                                                ({{$statement->product_variant_name}})
                                            @endif <br/>
                                            <strong> Vendor Name: </strong> {{$statement->vendor_name}}

                                        </td>
                                        <td>{{$statement->store_name}}  ({{$statement->store_code}})</td>
                                        <td>{{getReadableDate($statement->order_date)}}</td>
                                        <td>{{ucwords(str_replace('_',' ',$statement->order_type))}}<br/>

                                            @if($statement->link)
                                                (<a href="{{$statement->link}}" target="_blank">{{$statement->order_code}}</a>)
                                            @else
                                               ({{$statement->order_code}})
                                            @endif
                                        </td>
                                        <td>{{$statement->rejected_qty}}</td>
                                        <td>{{getNumberFormattedAmount($statement->unit_rate)}}</td>
                                        <td>{{getNumberFormattedAmount($statement->total_amount)}}</td>
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
                            {{$rejectedItemStatement->appends($_GET)->links()}}
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('scripts')
    @includeIf('Reporting::admin.wh-rejected-item-reporting.rejected-item-statement.rejected-item-statement-scripts');
@endpush

