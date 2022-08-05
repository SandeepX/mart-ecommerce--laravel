<script>
    $(document).ready(function () {

        let productFormData={};
        let preOrderProductFormData={};
        let warehousePreOrderCode="{{$warehousePreOrder->warehouse_preorder_listing_code}}";

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


        function displayErrorMessage(data,flashElementId='showFlashMessage') {

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

        function serializeFormDataToObject(arr){
            var o = {};
            var a = arr;
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        }

        $("#product-filter-form").on("submit", function (event) {
            event.preventDefault();
            var productSearchData=$(this).serializeArray();
            productFormData={};//resetting the object
            productFormData= serializeFormDataToObject(productSearchData);

            delete productFormData.page; //deleting page query string

            loadVendorProductsAjax(productFormData);
        });

        $(document).on('click', '#products-tbl-pagination .pagination a', function (e) {
            e.preventDefault();
            var pageNumber=$(this).attr('href').split('page=')[1];
            productFormData['page'] =pageNumber;
            loadVendorProductsAjax(productFormData);
        });

        //populates product filter list table
        function loadVendorProductsAjax(formData) {

            $.ajax({
                type: 'GET',
                url: "/api/warehouse/filter-products/vendor",
                data: formData,
                // dataType: 'html',
            }).done(function(response) {
                //console.log(response);
                $('#showFlashMessage').removeClass().empty();
                $("#product_list_tbl").empty().html(response);

            }).fail(function(data) {
                displayErrorMessage(data);
            });

        }

        //set price modal on click
        $(document).on('click','.add-to-cart-btn',function (e) {
            e.preventDefault();
            var productName = $(this).attr('data-product-name');
            var productCode = $(this).attr('data-product-code');
            var warehousePreOrderCode = "{{$warehousePreOrder->warehouse_preorder_listing_code}}";

            if(productCode,warehousePreOrderCode){
                let targetUrl="{{route('warehouse.warehouse-pre-orders.set-price.create',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";
                targetUrl = targetUrl.replace(':warehousePreOrderCode', warehousePreOrderCode);
                targetUrl = targetUrl.replace(':productCode', productCode);
                $.ajax({
                    type: 'GET',
                    url: targetUrl,
                    // dataType: 'html',
                }).done(function(response) {
                    $('#showFlashMessageModal').removeClass().empty();
                    $('#showFlashMessageCreateModal').removeClass().empty();
                    $("#price-setting-form-modal").empty().html(response);
                    let formAction="{{route('warehouse.warehouse-pre-orders.set-price.store',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";
                    formAction = formAction.replace(':warehousePreOrderCode', warehousePreOrderCode);
                    formAction = formAction.replace(':productCode', productCode);
                  // $('#priceSettingForm').attr('action',formAction);
                    $('#priceSettingSubmitBtn').attr('data-action',formAction);
                    $('#priceSettingModal').modal({
                        focus: false,
                        backdrop:false,
                    });

                    $('#showFlashMessage').removeClass().empty();

                }).fail(function(data) {
                    displayErrorMessage(data,'showFlashMessageModal');
                });
            }
        });

     /*   $("#priceSettingForm").on("submit", function (event) {
            event.preventDefault();
            $('#showFlashMessageModal').removeClass().empty();
            let priceSettingFormData = new FormData(this);
            let formAction = $(this).attr('action');
            ///console.log(formAction);

            if (priceSettingFormData && formAction){
                submitProductPriceSetting(priceSettingFormData,formAction);
            }
        });*/

        $("#priceSettingSubmitBtn").on("click", function (event) {
            event.stopPropagation();
            event.preventDefault();
            $('#showFlashMessageModal').removeClass().empty();
            let priceSettingFormData = new FormData(document.getElementById("priceSettingForm"));
            let formAction = $(this).attr('data-action');

            if (priceSettingFormData && formAction){
                submitProductPriceSetting(priceSettingFormData,formAction);
            }
        });

        function submitProductPriceSetting(priceSettingFormData,formAction) {
            $.ajaxSetup({
                headers:
                    { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
            });

            $.ajax({
                url: formAction,
                method: "POST",
                data: priceSettingFormData,
                datatype: "JSON",
                contentType : false,
                cache : false,
                processData: false
            }).done(function(response) {
               // window.location.reload();
                $('#showFlashMessageCreateModal').removeClass().empty();
                $('#priceSettingModal').modal('hide');
               // $("#pre-order-product_list_tbl").empty().html(response);
                $('#showFlashMessage').removeClass().addClass('alert alert-success').show().empty().html(
                    closeButton + 'Product added to pre-order successfully');
                $("#showFlashMessage").fadeOut(10000);
                window.scrollTo(0, 0);

                warehousePreOrderCode = response.data;
                if (warehousePreOrderCode){

                        loadPreOrderProductsAjax(warehousePreOrderCode);


                }
            }).fail(function(data) {
                displayErrorMessage(data,'showFlashMessageCreateModal');
            });

        }

        //populates preorder products  list table
        function loadPreOrderProductsAjax(warehousePreOrderCode,formData=null)
        {
            let targetUrl="{{route('api.warehouse.warehouse-pre-orders.products',['warehousePreOrderCode'=>':warehousePreOrderCode'])}}";
            targetUrl = targetUrl.replace(':warehousePreOrderCode', warehousePreOrderCode);
            $.ajax({
                type: 'GET',
                url: targetUrl,
                data: formData,
                // dataType: 'html',
            }).done(function(response) {
               // $('#showFlashMessage').removeClass().empty();
                $("#pre-order-product_list_tbl").empty().html(response);

            }).fail(function(data) {
                displayErrorMessage(data);
            });

        }

        $(document).on('click', '#pre-order-products-tbl-pagination .pagination a', function (e) {
            e.preventDefault();
            var pageNumber=$(this).attr('href').split('page=')[1];
            preOrderProductFormData['page'] =pageNumber;
            loadPreOrderProductsAjax(warehousePreOrderCode,preOrderProductFormData);
        });


        //edit variant price modal on click
        $(document).on('click','.edit-variant-btn',function (e) {
            e.preventDefault();

            var productCode = $(this).attr('data-product-code');
            var warehousePreOrderCode = "{{$warehousePreOrder->warehouse_preorder_listing_code}}";

            if(productCode,warehousePreOrderCode){
                let targetUrl="{{route('warehouse.warehouse-pre-orders.edit-price',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";
                targetUrl = targetUrl.replace(':warehousePreOrderCode', warehousePreOrderCode);
                targetUrl = targetUrl.replace(':productCode', productCode);
                $.ajax({
                    type: 'GET',
                    url: targetUrl,
                    // dataType: 'html',
                }).done(function(response) {
                    $('#showFlashMessageUpdateModal').removeClass().empty();
                    $("#price-update-form-modal").empty().html(response);
                    {{--let formAction="{{route('warehouse.warehouse-pre-orders.update-price',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";--}}
                    {{--formAction = formAction.replace(':warehousePreOrderCode', warehousePreOrderCode);--}}
                    {{--formAction = formAction.replace(':productCode', productCode);--}}
                   // $('#priceSettingForm').attr('action',formAction);
                   // $('#priceSettingSubmitBtn').attr('data-action',formAction);


                    $('#priceUpdateModal').modal({
                        focus: false,
                        backdrop:false,
                    });

                    $('#showFlashMessage').removeClass().empty();

                }).fail(function(data) {
                    displayErrorMessage(data);
                });
            }
        });


        //packaging disable  modal on click
        $(document).on('click','.package-disable-btn',function (e) {
            e.preventDefault();
            var productCode = $(this).attr('data-product-code');
            var warehousePreOrderCode = "{{$warehousePreOrder->warehouse_preorder_listing_code}}";

            if(productCode,warehousePreOrderCode){
                let targetUrl="{{route('warehouse.warehouse-pre-orders.edit-packaging',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";
                targetUrl = targetUrl.replace(':warehousePreOrderCode', warehousePreOrderCode);
                targetUrl = targetUrl.replace(':productCode', productCode);
                $.ajax({
                    type: 'GET',
                    url: targetUrl,
                    // dataType: 'html',
                }).done(function(response) {
                   $('#showFlashMessagePackageUpdateModal').removeClass().empty();
                   $("#package-update-form-modal").empty().html(response);
                    {{--let formAction="{{route('warehouse.warehouse-pre-orders.update-price',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";--}}
                    {{--formAction = formAction.replace(':warehousePreOrderCode', warehousePreOrderCode);--}}
                    {{--formAction = formAction.replace(':productCode', productCode);--}}
                    // $('#priceSettingForm').attr('action',formAction);
                    // $('#priceSettingSubmitBtn').attr('data-action',formAction);


                    $('#packageUpdateModal').modal({
                        focus: false,
                        backdrop:false,
                    });

                    $('#showFlashMessage').removeClass().empty();

                }).fail(function(data) {
                    displayErrorMessage(data);
                });
            }
        });

        $(document).on('submit','.package-update-form',function (event) {
            event.preventDefault();
            $('#showFlashMessageUpdateModal').removeClass().empty();
            let packageSettingFormData = new FormData(this);
            let formAction = $(this).attr('action');
            ///console.log(formAction);

            if (packageSettingFormData && formAction){
                $.ajaxSetup({
                    headers:
                        { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
                });

                $.ajax({
                    url: formAction,
                    method: "POST",
                    data: packageSettingFormData,
                    datatype: "JSON",
                    contentType : false,
                    cache : false,
                    processData: false
                }).done(function(response) {
                   // console.log(response);
                    // window.location.reload();
                    // $("#pre-order-product_list_tbl").empty().html(response);
                    $('#showFlashMessagePackageUpdateModal').removeClass().addClass('alert alert-success').show().empty().html(
                        closeButton + 'Product packaging updated successfully');

                }).fail(function(data) {
                    displayErrorMessage(data,'showFlashMessagePackageUpdateModal');
                });
            }
        });

        $(document).on('submit','.price-update-form',function (event) {
            event.preventDefault();
            $('#showFlashMessageUpdateModal').removeClass().empty();
            let priceSettingFormData = new FormData(this);
            let formAction = $(this).attr('action');
            ///console.log(formAction);

            if (priceSettingFormData && formAction){
                updateProductPriceSetting(priceSettingFormData,formAction);
            }
        });

        function updateProductPriceSetting(priceSettingFormData,formAction) {
            $.ajaxSetup({
                headers:
                    { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
            });

            $.ajax({
                url: formAction,
                method: "POST",
                data: priceSettingFormData,
                datatype: "JSON",
                contentType : false,
                cache : false,
                processData: false
            }).done(function(response) {
                // window.location.reload();
                // $("#pre-order-product_list_tbl").empty().html(response);
                $('#showFlashMessageUpdateModal').removeClass().addClass('alert alert-success').show().empty().html(
                    closeButton + 'Product price updated successfully');

            }).fail(function(data) {
                displayErrorMessage(data,'showFlashMessageUpdateModal');
            });

        }

        $(document).on('click','.toggle-status-btn',function (event) {
            event.preventDefault();

            let targetUrl= $(this).attr('href');
            let toggleButton = $(this);
            $.ajax({
                type: 'GET',
                url: targetUrl,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessageUpdateModal').removeClass().addClass('alert alert-success').show().empty().html(
                    closeButton + response.message);
                let activeStatus = response.data.status;
                let warehousePreOrderCode = response.data.warehouse_preorder_code;
                let btnText;
                let activeStatusDivHtml;
                if (activeStatus){
                    btnText='Deactivate';
                    activeStatusDivHtml="<span class='label label-success'>On</span>";
                }
                else {
                    btnText='Activate';
                    activeStatusDivHtml="<span class='label label-danger'>Off</span>";
                }
                let btnHtml =" <i class='fa fa-pencil'>"+btnText+"</i>";


                toggleButton.html(btnHtml);

               let activeStatusDiv= toggleButton.parent().siblings('.active-status-div');
               activeStatusDiv.html(activeStatusDivHtml);

                if (warehousePreOrderCode){
                    loadPreOrderProductsAjax(warehousePreOrderCode);
                }

            }).fail(function(data) {
                displayErrorMessage(data,'showFlashMessageUpdateModal');
            });
        });

        $(document).on('click','.delete-pre-product-btn',function (event) {
            event.preventDefault();
            let deleteButton = $(this);
            if (confirm("Are you sure you want to delete ? ")) {
                let targetUrl= $(this).attr('href');
                $.ajaxSetup({
                    headers:
                        { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
                });
                $.ajax({
                    type: 'DELETE',
                    url: targetUrl,

                }).done(function(response) {
                    $('#showFlashMessageUpdateModal').removeClass().empty();
                    let parentForm= deleteButton.parents('form');
                    parentForm.remove();

                    warehousePreOrderCode = response.data;
                    if (warehousePreOrderCode){
                        loadPreOrderProductsAjax(warehousePreOrderCode);
                    }

                    let totalForms=$('.form-div form').length;
                    if (totalForms < 1){
                        $('#priceUpdateModal').modal('hide');
                    }


                }).fail(function(data) {
                    displayErrorMessage(data,'showFlashMessageUpdateModal');
                });
            }

        });

        $(document).on('click','.bulk-delete',function (e){
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete all preorderProducts ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulk-delete').submit();
                } else if (result.isDenied) {
                    Swal.fire('Product delete Action cancelled', '', 'info')
                }
            })
        })

    });
</script>
