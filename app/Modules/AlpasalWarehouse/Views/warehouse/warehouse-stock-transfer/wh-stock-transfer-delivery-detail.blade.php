@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb', [
        'page_title' => $title,
        'sub_title' => "Add Delivery Detail of {$title}",
        'icon' => 'home',
        'sub_icon' => '',
        'manage_url' => route($base_route.'.index'),
    ])
    <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add Delivery Detail of {{$title}}</h3>
                        </div>

                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        <div id="showDeliveryFlashMessage"></div>
                        @can('View WH Stock Transfer List')
                            <div class="box-body">
                                <div class="row">
                                    @if(isset($stockTransferDeliveryStatus) && $stockTransferDeliveryStatus != 'received')
                                        <div class="col-xs-12 col-md-6">
                                            <form id="stock-transfer-delivery-detail-form" enctype="multipart/form-data">
                                                <div class="col-xs-12">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title">
                                                                Add Delivery Detail
                                                            </h3>
                                                        </div>
                                                        @include('AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.delivery-detail-form')
                                                        <div class="box-footer">
                                                            <button type="submit" class="btn btn-primary form-control">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    Delivery Detail
                                                </h3>

                                            </div>

                                            <div class="panel-body">
                                                <div class="box-body">
                                                    <table class="table table-bordered table-striped" cellspacing="0" width="100%" id="delivery-detail-tbl">
                                                        <thead>
                                                        <tr>
                                                            <th>Field</th>
                                                            <th>Value</th>
                                                            {{--                                    <th>Action</th>--}}
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($warehouseStockTransferDeliveryDetail as $stockTransferMeta)
                                                            <tr>
                                                                <td>{{ Str::ucfirst(str_replace('_', ' ', $stockTransferMeta->key)) }}</td>
                                                                <td>
                                                                    <?php $value = explode('.', $stockTransferMeta->value); ?>
                                                                    @if(isset($value[1]))
                                                                        @if(in_array($value[1], ['jpeg', 'jpg', 'png']))
                                                                            <img src="{{photoToUrl($stockTransferMeta->value, asset('uploads/warehouse-stock-transfer/delivery-detail'))}}" alt="delivery image" height="100px" width="150px">
                                                                        @elseif(in_array($value[1], ['txt', 'pdf', 'doc', 'docx']))
                                                                                <a href="{{photoToUrl($stockTransferMeta->value, asset('uploads/warehouse-stock-transfer/delivery-detail'))}}" target="_blank">View file</a>
                                                                        @endif
                                                                    @else
                                                                        {{ Str::ucfirst($stockTransferMeta->value) }}
                                                                    @endif
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
                                </div>

                            </div>
                        @endcan
                    </div>
                    <!-- /.box -->
                </div>
                <!--ends column-->
            </div>
            <!-- ends row-->
        </section>
        <!--ends section-->
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $('#stock-transfer-delivery-detail-form').submit(function (e) {
                e.preventDefault();
                let deliveryDetailFormData = new FormData(document.getElementById('stock-transfer-delivery-detail-form'));
                $.ajaxSetup({
                    headers:
                        { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
                });
               $.ajax({
                   url: '{{ route($base_route.".add-delivery-detail", $stockTransferCode) }}',
                   type: 'POST',
                   data: deliveryDetailFormData,
                   datatype: "JSON",
                   contentType : false,
                   cache : false,
                   processData: false
               }).done(function (response) {
                   $('#delivery-detail-tbl').append(response.html);
                   document.getElementById("stock-transfer-delivery-detail-form").reset();
               }).fail(function (data) {
                   displayErrorMessage(data);
               });
            });

        var deliveryFile = '<div id="delivery-detail-file-section">' +
                '<input type="file" name="file" id="delivery_detail_value" required>' +
                '<button class="btn btn-success" style="cursor: pointer; margin-top: 10px;" id="delivery-detail-enter-data">'+'Enter Data'+'</button>'+
            '</div>';
        var deliveryInput = '<div id="delivery-detail-input-section">' +
            '<input type="text" class="form-control" name="value" id="delivery_detail_value" required>' +
            '<button class="btn btn-success" style="cursor: pointer; margin-top: 10px;" id="delivery-detail-upload-file">Upload File</button>' +
            '</div>';

            $('body').on('click', '#delivery-detail-upload-file', function () {
                $('#delivery-detail-input-section').remove();
                $('#delivery_detail_value').after(deliveryFile);
            });
            $('body').on('click', '#delivery-detail-enter-data', function () {
                $('#delivery_detail_value').after(deliveryInput);
                $('#delivery-detail-file-section').remove();
            });
        });

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


        function displayErrorMessage(data,flashElementId='showDeliveryFlashMessage') {

            flashElementId='#'+flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            /* if (data.status == 500) {
                 flashMessage.html(closeButton + data.responseJSON.errors);
             }
             if (data.status == 400 || data.status == 419) {
                 flashMessage.html(closeButton + data.responseJSON.message);
             }*/
            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            }
            else{
                flashMessage.html(closeButton + data.responseJSON.message);
            }
        }
    </script>
@endpush
