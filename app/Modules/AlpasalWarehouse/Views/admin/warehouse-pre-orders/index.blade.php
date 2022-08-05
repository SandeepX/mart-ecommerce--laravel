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
                <div class="col-xs-6">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            @include(''.$module.'.admin.warehouse-pre-orders.common.filter-form')
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    @can('Clone Admin Warehouse Products')
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @include(''.$module.'.admin.warehouse-pre-orders.common.clone-products-form-preorder-listing')
                        </div>
                    </div>
                    @endcan
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Warehouses Having PreOrders
                            </h3>
                        </div>

                        <div class="box-body">
                            <table  class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>No Of Pre Orders</th>
                                    <th>Last Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warehouses as $i => $warehouse)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$warehouse->warehouse_name}}</td>
                                        <td>{{$warehouse->warehouse_pre_order_listings_count}}</td>
                                        <td>{{date('Y-m-d',strtotime($warehouse->last_created_at))}}</td>
                                        @can('View List Of Pre Orders')
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Detail ',route('admin.warehouse-pre-orders.show', $warehouse->warehouse_code),'Show warehouse', 'eye','info')!!}
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                            {{$warehouses->appends($_GET)->links()}}
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
<script>
    $('#clone-button').on('click',function (event){
        var sourceCode = $('#source_listing_code').val();
        var destinationCode = $('#destination_listing_code').val();
        event.preventDefault();
        Swal.fire({
            title: 'Are You Sure You want To Clone From '+ sourceCode+' To '+ destinationCode+'?',
            showCancelButton: true,
            customClass: 'swal-wide',
            cancelButtonColor: '#d33',
            confirmButtonText: `Clone`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                  $('#clone-form').submit();
            }
        });

    });
</script>

@endpush
