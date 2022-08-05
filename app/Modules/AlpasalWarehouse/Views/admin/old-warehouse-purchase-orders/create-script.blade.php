<script>
    $(document).ready(function() {

        var vendorSelected = $('#vendor_code').val();



        initializeSelect2Product("#product_1");
        initializeSelect2ProductVariant("#product_variant_1");

        var products = '<td style="width: 40%">' +
            '<select required  name="product_code[]" class="select2_product form-control">' +
            '<option value="">Choose Product </option>' +
            '</select>' +
            '</td>';

        var product_variants = '<td style="width: 20%">' +
            '<select disabled name="product_variant_code[]" class="select2_product_variant form-control" id="product_variant_code"  >' +

            '</select>' +
            '</td>';

        var qty = '<td>' +
            '<input required  type="number" id="qty" class="form-control"   value="" name="qty[]">' +
            '</td>';

        var minus_btn = '<td>' +
            '<a id="minus_record" class="btn btn-danger btn-sm delete-record" data-id="2">' +
            '<i class="fa fa-minus"></i></a>' +
            '</td>';

        $('.sample_new_row').append(products).append(product_variants).append(qty).append(minus_btn);



        // Initialize select2
        function initializeSelect2Product(selector) {
            $(selector).select2();
        }

        function initializeSelect2ProductVariant(selector) {
            $(selector).select2({
                'placeholder': '--'
            });
        }


        /* --------- Add Row ----- */

        $("#add_row").click(function(e) {

            e.preventDefault();




            var content = $('#sample_table tr'),
                size = $('#tbl_order >tbody >tr').length + 1,

                element = content.clone();
            element.attr('id', 'rec-' + size);
            var dyn_product_id = 'product_' + size;
            var dyn_product_variant_id = 'product_variant_' + size;

            element.find('.select2_product').attr('id', dyn_product_id);
            element.find('.select2_product_variant').attr('id', dyn_product_variant_id);


            element.find('.delete-record').attr('data-id', size);
            element.appendTo('#tbl_order_body');

            initializeSelect2Product(("#" + dyn_product_id));
            initializeSelect2ProductVariant(("#" + dyn_product_variant_id));
        });

        /* --------- Delete Row ----- */

        $(document).delegate('a.delete-record', 'click', function(e) {
            e.preventDefault();
            console.log($(this).closest('tr'))
            if ($(this).closest('tr').attr('id') != 'rec-1') {
                if (confirm("Are you sure you want to delete ? ")) {
                    var id = $(this).attr('data-id');
                    $('#rec-' + id).remove();
                }
            }

        });



        /* ------- on change product ---- */

        $(document).delegate('.select2_product', 'select2:open', function(e) {
            console.log('i amopeneing',$('#vendor_code').val())
            var elementID = $(this).attr('id');
            console.log('elID',"#"+elementID)
            vendorSelection = $('#vendor_code').val();
            console.log('vs',vendorSelection)
            var select2AjaxObj = {};

            if (vendorSelection) {

                var productFilterUrl = "{{ url('api/filter-products/vendor/') }}"  + "/" + vendorSelection;



                console.log(productFilterUrl);
                select2AjaxObj = {
                    placeholder: 'Please Type Product Name',
                    ajax: {

                        url: productFilterUrl,
                        data: function(params) {
                            return {
                                product_search_term: params.term

                            };
                        },
                        dataType: 'json',

                        delay: 250,

                        processResults: function(data) {


                            return {

                                results: $.map(data, function(item) {

                                    return {

                                        text: item.product_name,

                                        id: item.product_code

                                    }

                                })

                            };

                        },

                        cache: true
                    }
                };
            }

            $('#'+elementID).empty();
            $('#'+elementID).removeData();
            $("#"+elementID).select2(select2AjaxObj);
        });



        $(document).delegate('.select2_product', 'change', function(e) {
            e.preventDefault();


            var selectedProduct = $(this).val();
            var nextProductVariantSelect = $(this).closest('tr').find('.select2_product_variant');
            if (selectedProduct) {
                $.ajax({
                    url: "{{ url('api/filter-product-variants/product/') }}" + "/" +
                    selectedProduct,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var variantTag = response.variant_tag;
                        var variantOptions = response.product_variants;
                        if (variantTag) {
                            nextProductVariantSelect.removeAttr('disabled');
                            nextProductVariantSelect.attr('required', true);

                            nextProductVariantSelect.empty();
                            var content =
                                '<option selected disabled value=" ">Select A Variant</option>';

                            $.each(variantOptions, function(index, vOption) {
                                content += '<option value="' + vOption
                                        .product_variant_code + '">' + vOption
                                        .product_variant_name + '</option>';
                            });
                            console.log(content)
                            nextProductVariantSelect.append(content);
                        } else {
                            nextProductVariantSelect.attr('disabled', true);
                            nextProductVariantSelect.attr('required', false);
                            nextProductVariantSelect.empty();

                        }


                        console.log(response)

                    },
                    error: function(error) {
                        nextProductVariantSelect.empty();
                        nextProductVariantSelect.attr('disabled', true);
                        nextProductVariantSelect.attr('required', false);


                    }
                })
            }



        });

        /*----- on vendor change ----*/

        $(document).delegate('#vendor_code', 'change', function(e) {
            e.preventDefault();
            vendorSelected = $(this).val();

            if (vendorSelected) {

                if (confirm("Warning : Changing Vendor Deletes the Ordering Products Selection !")) {
                    var firstRow = $("#tbl_order tbody tr:first");


                    $('#tbl_order_body tr').each(function(index, tableRow) {
                        var row = tableRow;
                        if (index !== 0) {
                            row.remove();
                        }
                    });

                    firstRow.find('.select2_product').val(null).trigger('change');
                    firstRow.find('.select2_product_variant').val(null).trigger('change').attr(
                        'disabled', true);
                    firstRow.find('#qty').val('');
                }


            }
        });

        /*---------- on form submitting : draft or submit (direct) -----*/


        $('#save_as_draft').click(function(e) {
            e.preventDefault();

            //            var products = [];
            //            var productVariants = [];
            //            $("select[name^=product_code]").each(function () {products.push($(this).val());});
            //            $("select[name^=product_variant_code]").each(function () {productVariants.push($(this).val());});

            $(this).text('Please Wait .....');
            $("#submit_type").val('draft');
            // var orderData = new FormData("#order_form");
            var orderData = $("#order_form").serialize();
            submitData(orderData);

            // var form_data = new FormData();
            // //            form_data.append('name', $('#name').val());
            // //            form_data.append('company_code', $('#company_code').val());
            // //            form_data.append('address', $('#address').val());
            // form_data.append('product_codes', []);
            // form_data.append('product_variant_codes', []);


            // console.log(form_data);
            // //            form_data.append('company_type', $('#company_type').val());
            // //            form_data.append('logo', document.getElementById('company_logo').files[0]);

        });

        $('#submit_order').click(function(e) {
            e.preventDefault();
            $("#submit_type").val('sent');
            var orderData = $("#order_form").serialize();
            submitData(orderData);
        });


        function submitData(orderData) {
            var closeButton =
                '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('admin.warehouse-purchase-orders.store') }}",
                method: "POST",
                data: orderData,
            }).done(function(data) {
                window.location.reload();
                $('#showFlashMessage').removeClass().addClass('alert alert-success').show().empty()
                    .html(
                        closeButton + data
                            .message);

            }).fail(function(data) {
                if (data.status == 500) {
                    $('#showFlashMessage').removeClass().addClass('alert alert-danger').show().empty()
                        .html(
                            closeButton + data
                                .responseJSON.errors);

                }
                if (data.status == 400) {
                    $('#showFlashMessage').removeClass().addClass('alert alert-danger').show().empty()
                        .html(
                            closeButton + data
                                .responseJSON.message);

                }
                if (data.status == 422) {
                    var errorString = "<ol type='1'>";
                    for (error in data.responseJSON.errors) {
                        errorString += "<li>" + data.responseJSON.errors[error] + "</li>";
                    }
                    errorString += "</ol>";
                    $('#showFlashMessage').removeClass().addClass('alert alert-danger').show().empty()
                        .html(
                            closeButton + errorString);
                }
            });

        }

    });

</script>