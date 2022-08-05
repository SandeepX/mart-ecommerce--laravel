@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("AdminWarehouse::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
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

                            @include("AdminWarehouse::layout.partials.flash_message")
                            @if($minOrderSettings->count() <= 0)
                                <h3 class="box-title">Add A {{$title}}</h3>
                            <form class="form-horizontal" role="form" id="createMinOrderSetting" action="{{route($base_route.'.store')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">

                                    @include(''.$module.'.warehouse.settings.min-order-settings.common.create-form')

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 34%;" class="btn btn-block btn-primary addBrand">Add</button>
                                </div>
                            </form>
                             @endif
                        </div>

                        <!-- /.box-header -->
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Minimum Order Settings
                            </h3>

                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Setting Warehouse Min Order Code</th>
                                    <th>Warehouse Code</th>
                                    <th>Minimum Order Amount</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($minOrderSettings as $minOrderSetting)
                                    <tr>
                                        <td>{{$minOrderSetting->id}}</td>
                                        <td>{{$minOrderSetting->warehouse_min_order_amount_setting_code}}</td>
                                        <td>{{$minOrderSetting->warehouse_code}}</td>
                                        <td>{{$minOrderSetting->min_order_amount}}</td>
                                        <td>@if($minOrderSetting->status == 1)
                                            <a href="{{route('warehouse.min-order-settings.changeMinOrderStatus',$minOrderSetting->warehouse_min_order_amount_setting_code)}}" class="btn btn-xs btn-success">
                                                Active</a>
                                            @elseif($minOrderSetting->status == 0)
                                                <a href="{{route('warehouse.min-order-settings.changeMinOrderStatus',$minOrderSetting->warehouse_min_order_amount_setting_code)}}" class="btn btn-xs btn-danger">
                                                    Inactive</a>
                                            @endif
                                        </td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($minOrderSetting->created_at))}}</td>
                                        <td>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('warehouse.min-order-settings.edit', $minOrderSetting->warehouse_min_order_amount_setting_code),'Edit ', 'pencil','primary')!!}
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('warehouse.min-order-settings.destroy',$minOrderSetting->warehouse_min_order_amount_setting_code),$minOrderSetting,'Min-order-Settings','')!!}
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
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection

@push('scripts')

    <script>
        $('#createMinOrderSetting').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to create New Min Order Setting  ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });
    </script>
@endpush

