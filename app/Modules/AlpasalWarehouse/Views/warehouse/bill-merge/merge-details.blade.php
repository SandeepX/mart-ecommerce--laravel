@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>
                             @can('View Bill Merge Master List')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('warehouse.bill-merge.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                      Back To Bill Merge Lists
                                    </a>
                                </div>
                             @endcan
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bill Merge Detail Code</th>
                                    <th>Bill Type</th>
                                    <th>Bill Code</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($mergeDetails as $i => $mergeDetail)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $mergeDetail->bill_merge_details_code}}</td>
                                        <td>{{ ucwords($mergeDetail->bill_type)}}</td>
                                        <td>{{ $mergeDetail->bill_code }}</td>
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
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
