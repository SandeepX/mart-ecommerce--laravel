<script type="text/javascript">
    $(function () {
        let formProductData = {};
        $('body').on('click', '.product_class', function () {

            $.ajaxSetup({
                headers: {"X-Test-Header": "test-value"}
            })

            $.ajax({
                {{--url: '{{ route($base_route.".add-products-table", $stockTransferCode) }}',--}}
                url: '{{ route("warehouse.wh-product-lists", $stockTransferCode) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    warehouse_product_master_code: $(this).data('warehouse_product_master_code')
                },
            }).done(function (response) {
                $('#selected_products_tbl').append(response.html);

            }).fail(function (data) {
                displayErrorMessage(data);
            });
        });

        $('body').on('click', '.remove-row', function () {
            var $this = $(this);
            let stock_transfer_details_code = $this.data('stock_transfer_details_code');
            console.log(stock_transfer_details_code);
            if (stock_transfer_details_code) {
                $.ajaxSetup({
                    headers: {"X-Test-Header": "test-value"}
                })

                $.ajax({
                    url: '{{ route($base_route.".delete-stock-details", $stockTransferCode) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        stock_transfer_details_code: stock_transfer_details_code
                    }
                }).done(function (response) {
                    $this.closest('tr').remove();
                }).fail(function (data) {
                    displayErrorMessage(data);
                });
            } else {
                $this.closest('tr').remove();
            }
        })

        $('body').on('keyup', '.product-row-input input', function () {
            var $this = $(this);
            let value = $this.val();
            let product_price = $this.data('product_price');
            $this.closest('tr').find('.product-subtotal').html(value * product_price);
        })
    });

    function serializeFormDataToObject(arr) {
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

    $("#product-filter-form").submit(function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        let form = $(this);
        formProductData = {};
        formProductData = serializeFormDataToObject(form.serializeArray());
        delete formProductData.page;
        loadStockTransferProductsAjax(formProductData)
    })

    $(document).on('click', '#paginate_product_table .pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        formProductData['page'] = page;
        loadStockTransferProductsAjax(formProductData);

    });

    function loadStockTransferProductsAjax(formData) {
        $.ajax({
            type: "GET",
            url: "{{ route('warehouse.stock-transfer.get-product-lists', $stockTransferCode) }}",
            data: formData,
        }).done(function (response) {
            $('#product-table').html(response.html);
        }).fail(function (data) {
            displayErrorMessage(data);
        });
    }

    $('#save_products_as_draft').click(function (e) {
        e.stopPropagation();
        e.preventDefault(); // avoid to execute the actual submit of the form.
        let draftFormData = new FormData(document.getElementById('product-save-as-draft-form'));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        $.ajax({
            type: "POST",
            url: "{{route($base_route.'.add-products-stock-transfer-details-draft', $warehouseStockTransfer->stock_transfer_master_code)}}",
            data: draftFormData, // serializes the form's elements.
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            alert('Products data successfully as a draft!');
            location.reload();
        }).fail(function (data) {
            displayErrorMessage(data);
        });
    });

    //close btn of error message
    var closeButton =
        '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


    function displayErrorMessage(data, flashElementId = 'showFlashMessage') {

        flashElementId = '#' + flashElementId;
        var flashMessage = $(flashElementId);
        flashMessage.removeClass().addClass('alert alert-danger').show().empty();

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
        } else {
            flashMessage.html(closeButton + data.responseJSON.message);
        }
    }

    $(document).ready(function () {

    });
</script>
