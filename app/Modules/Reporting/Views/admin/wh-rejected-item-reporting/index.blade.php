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

            /*table {*/
            /*    display: block;*/
            /*    overflow-x: auto;*/
            /*    white-space: nowrap;*/
            /*}*/

        </style>
        <!-- Main content -->
        <section class="content">

            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>

            <div class="row">
                @include(''.$module.'admin.wh-rejected-item-reporting.filter')

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
                                 Warehouse Rejected Item Report
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.wh-rejected-item-report-statement.index')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-tasks"></i>
                                    Rejected Item Statement
                                </a>
                            </div>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.wh-rejected-item-excel-export')}}" style="border-radius: 0px; " class="btn btn-sm btn-success excel-export">
                                    <i class="fa fa-file"></i>
                                   Excel Export
                                </a>
                            </div>
                        </div>


                        <div id="tableForProductsList">

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
    @includeIf('Reporting::admin.wh-rejected-item-reporting.rejected-item-report-scripts');

    <script>
           $('.excel-export').on('click',function(e){
               e.preventDefault();
               var url = $(this).attr('href');
               let query = {
                   warehouseCode: $('#warehouse').val(),
                   vendor: $('#vendor').val(),
                   product: $('#product').val(),
                   from_date: $('#from_date').val(),
                   to_date: $('#to_date').val(),
                   page : 1
               }
                var excelDownloadUrl = url +'?' + $.param(query)
                window.location = excelDownloadUrl;
           });
    </script>
@endpush

