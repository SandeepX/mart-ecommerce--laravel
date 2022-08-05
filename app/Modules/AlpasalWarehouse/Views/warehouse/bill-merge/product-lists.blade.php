@extends('AdminWarehouse::layout.common.masterlayout')
@push('css')
    <style>
        .bill_merge_table {
            width: 100%;
        }
        .bill_merge_button {
            margin-top: 25px;
        }
        .bill_merge_select {
            width: 90%;
        }
        .bill_merge_select{
            width: 100%;
        }
        .bill_merge_section{
            width: 70px;
        }
        .bill_merge_table td,
        .bill_merge_table th {
            vertical-align: middle;
            margin-bottom: 10px;
            border: none;
        }
        .bill_merge_table thead tr,
        .bill_merge_table thead th {
            border: none;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: transparent;
        }
        .font-weight-bold {
            font-weight: bold;
        }

    </style>
@endpush
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

            @if (Session::has('stock_unavailable_items'))
                <div class="alert alert-danger">
                    <strong style="text-decoration: underline">Stock Unavailable Products</strong><br>
                    @foreach(session('stock_unavailable_items') as $stock_unavailable_item)
                        <strong>
                            Product : {{$stock_unavailable_item['product_name']}}<br>
                            @if($stock_unavailable_item['product_variant_name'])
                                Variant : {{$stock_unavailable_item['product_variant_name']}}<br>
                            @endif
                            Dispatching Qty : {{$stock_unavailable_item['dispatchingQty']}}<br>
                            Dispatching Micro Qty : {{$stock_unavailable_item['dispatchingMicroQty']}}<br>
                            Insufficient Qty : {{$stock_unavailable_item['insufficientQty']}}<br>
                        </strong><br>
                    @endforeach
                </div>
            @endif
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <b>List of Products</b>

                        @can('View Bill Merge Master List')
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('warehouse.bill-merge.index')}}"
                                   style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    Back To Merge List
                                </a>
                            </div>
                        @endcan

                        @can('Bill Merge Generate Bill')
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('warehouse.merge-bill.generate-bill.master',$masterBillMerge->bill_merge_master_code)}}"
                                   style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Generate Bill
                                </a>
                            </div>
                        @endcan

                    </div>
                    <div class="panel-body">
                        <div class="alert alert-danger" id="custom_alert" style="display: none" role="alert">
                            <strong class="error_message"></strong>
                        </div>
                        <section class="invoice">
                            <table class="table mainTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Vendor</th>
                                    <th>Is Taxable</th>
                                    <th>Status</th>
                                    <th>SubTotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($mergedProducts as $mergedProduct)
                                    <tr data-toggle="collapse" data-target="#{{$mergedProduct->bill_merge_product_code}}" class="accordion-toggle colapse-row {{($mergedProduct['package_micro_quantity'] > $mergedProduct->current_stock ) ? 'bg-danger' : ''}}" style="cursor: pointer">
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$mergedProduct->product_name}} @if($mergedProduct->product_variant_name) ({{$mergedProduct->product_variant_name}}) @endif -{{$mergedProduct->billMergeDetail->bill_code}} </td>
                                        <td>{{$mergedProduct->vendor_name}}</td>
                                        <td>{!! ($mergedProduct->is_taxable) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
                                        <td class="status{{$loop->index+1}}" >{{ucwords($mergedProduct->status)}} </td>
                                        <td>
                                            {{roundPrice($mergedProduct->subtotal)}}
                                        </td>
                                    </tr>
                                    @include('AlpasalWarehouse::warehouse.bill-merge.show-partials.sub-table')
                                @endforeach
                                </tbody>
                            </table>

                            @if($masterBillMerge->status =='pending')
                                @include('AlpasalWarehouse::warehouse.bill-merge.show-partials.status-form')
                            @endif

                        </section>
                    </div>

                </div>

            </div>

            <!-- /.row -->
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });

        $(document).ready(function (){
            // $('#status').on('change',function (){
            //     let status = $(this).val();
            //     if(status=='dispatched'){
            //         $('#dispatch-vehicle-details').show();
            //          requireddiv();
            //     }else{
            //         $('#dispatch-vehicle-details').hide();
            //          nonrequireddiv();
            //     }
            // });
            // let status = $('#status').val();
            // if(status =='dispatched'){
            //     $('#dispatch-vehicle-details').show();
            //     requireddiv();
            // }else{
            //     $('#dispatch-vehicle-details').hide();
            //     nonrequireddiv();
            // }

            $('.bill_merge_button').on('click',function (){
                var tableRow = $(this).attr('id');
                var className = 'status'+tableRow;
                var statusTable = $('.'+className).text();
                var singleQuantity = 'quantity'+tableRow;
                var singleStatus = 'singleStatus'+tableRow;
                var href = $(this).attr('href');
                var quantity = $('#'+singleQuantity).val();
                var status = $('#'+singleStatus).val();
                $.ajax({
                    type: "POST",
                    url: href,
                    data:{
                        quantity:quantity,
                        status:status,
                        _token: "{{ csrf_token() }}"
                    },
                }).done(function(data) {
                    const changedStatus = data.data.status;
                    let uppercasedSatus = changedStatus[0].toUpperCase() + changedStatus.substring(1);
                    $('#'+singleQuantity).val(data.data.quantity);
                    $('#'+singleStatus).val(data.data.status);
                    $("."+className).text(uppercasedSatus);
                    if(data.data.status === 'accepted'){
                        $("."+className).css({"background-color": "lightgreen", });
                    }else{
                        $("."+className).css({"background-color": "lightcoral", });
                    }
                }).fail(function(data){
                    $('#custom_alert').css('display','block');
                    $('.error_message').text(data.responseJSON.message);
                    $('#custom_alert').slideUp(4000);

                });
            });
        })

        function requireddiv(){
            $("#driver_name").prop('required',true);
            $("#vehicle_type").prop('required',true);
            $("#vehicle_number").prop('required',true);
            $("#expected_delivery_time").prop('required',true);
            $("#contact_number").prop('required',true);
        }

        function nonrequireddiv(){
            $("#driver_name").prop('required',false);
            $("#vehicle_type").prop('required',false);
            $("#vehicle_number").prop('required',false);
            $("#expected_delivery_time").prop('required',false);
            $("#contact_number").prop('required',false);
        }

        $('#status-form').submit(function (e, params) {
            var localParams = params || {};
            if (!localParams.send) {
                e.preventDefault();
            }
            Swal.fire({
                title: 'Are you sure you want to Change The Status?',
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
