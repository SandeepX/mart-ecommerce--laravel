<script>

    $(document).ready(function (){
        let selectedWarehouseName=  $('#warehouse').find('option:selected').text();
        var trimStr = $.trim(selectedWarehouseName);
        $('#selectedWarehouse').text(trimStr);
    })

    function productsFilterParams(){
        let params = {
            warehouse_code: $('#warehouse').val(),
            vendor_code: $('#vendor').val(),
            product_code: $('#product').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            stock_action : $('#stock_action').val(),
            page : 1
        }
        return params;
    }
    $(document).ready(function (){
        var selectedVendor = $('#vendor');
        var warehouse_code = $('#warehouse').val();
        var selectedProduct = $('#product');

        var params = productsFilterParams();

        ajaxForGettingResults(params);

        $('#warehouse').on('change',function(e){
            e.preventDefault();
            var warehouse_code = $(this).val();
            params.warehouse_code = warehouse_code;
            if(warehouse_code){

                selectedVendor.val(null).trigger('change');
                selectedVendor.select2({
                    placeholder:  '--- Select Vendor ---',
                    ajax: {
                        url: "{{ url('') }}"+"/warehouse/"+warehouse_code+"/vendors/list",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                vendor_name: params.term ,// search term
                                page: params.page,
                                paginate_by:20
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: $.map(response.data.data, function(vendor) {
                                    return {
                                        id: vendor.vendor_code,
                                        vendor_code: vendor.vendor_code,
                                        vendor_name : vendor.vendor_name
                                    }
                                }),
                                pagination: {
                                    more: (params.page * 20) < response.data.total
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    templateResult: formatVendor,
                    templateSelection: formatVendorSelection
                });

                selectedProduct.val(null).trigger('change');
                selectedProduct.select2({
                    placeholder:  '--- Select Product ---',
                    ajax: {
                        url: "{{ url('') }}"+"/warehouse/"+warehouse_code+"/products/list",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                product_name: params.term ,// search term
                                page: params.page,
                                paginate_by:20
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: $.map(response.data.data, function(product) {

                                    let id = product.product_code;
                                    if(product.product_variant_code){
                                        id += '-'+product.product_variant_code;
                                    }
                                    return {
                                        id:id ,
                                        product_code: product.product_code,
                                        product_name : product.name
                                    }
                                }),
                                pagination: {
                                    more: (params.page * 20) < response.data.total
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    templateResult: formatProduct,
                    templateSelection: formatProductSelection
                });
            }else{
                $('#vendor').val(null).trigger('change');
                $('#product').html('');
                $('#vendor').append('<option value="" disabled selected>Select Vendor</option>');
                $('#product').append('<option value="" disabled selected>Select Product</option>');
            }
        }).trigger('change');

        $('#vendor').on('change',function(e){
            let vendor_code = $(this).val();
            selectedProduct.val(null).trigger('change');
            selectedProduct.select2({
                placeholder:  '--- Select Product ---',
                ajax: {
                    url: "{{ url('') }}"+"/warehouse/"+warehouse_code+"/products/list?vendor_code="+vendor_code+"",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            product_name: params.term ,// search term
                            page: params.page,
                            paginate_by:20
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(response.data.data, function(product) {
                                return {
                                    id: product.product_code,
                                    product_code: product.product_code,
                                    product_name : product.name
                                }
                            }),
                            pagination: {
                                more: (params.page * 20) < response.data.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                templateResult: formatProduct,
                templateSelection: formatProductSelection
            });
        });

        $('#filter_form').on('submit',function (e){
            e.preventDefault();
            var params = productsFilterParams();
            ajaxForGettingResults(params);
        });
    });



    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function formatVendor (vendor) {
        if (vendor.loading) {
            return vendor.text; // display 'searching...' while searching
        }
        var markup = "";
        markup += "<option  value='"+vendor.vendor_code+"'>"+vendor.vendor_name+"</option>";
        return markup;
    }

    function formatProduct (product) {
        if (product.loading) {
            return product.text; // display 'searching...' while searching
        }
        var markup = "";
        markup += "<option  value='"+product.product_code+"'>"+product.product_name+"</option>";
        return markup;
    }

    function formatVendorSelection (vendor) {
        return vendor.vendor_name  || vendor.text;
    }

    function formatProductSelection (product) {
        return product.product_name  || product.text;
    }

    var closeButton =
        '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


    function displayErrorMessage(data,flashElementId='showFlashMessage') {

        flashElementId='#'+flashElementId;
        var flashMessage = $(flashElementId);
        flashMessage. removeClass().addClass('alert alert-danger').show().empty();

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

    function ajaxForGettingResults(params){

        $('#tableForProductsList').html('Loading Results ....')

        $.ajax({
            type: 'GET',
            url: "{{ route('admin.wh-stock-report.index') }}",
            data: params,
        }).done(function(response) {
            $('#tableForProductsList').html('');
            $('#tableForProductsList').html(response);
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessage');
            $("#showFlashMessage").fadeOut(10000);
            scroll(0,0);
        });
    }

    // function clean(obj) {
    //     for (var propName in obj) {
    //         if (obj[propName] === null || obj[propName] === "" || obj[propName] === undefined) {
    //             delete obj[propName];
    //         }
    //     }
    //     return obj
    // }

    function constructTitle(obj){
        let title = '';
        //title =  (Object.entries(obj)).join('/')
        for (const [key, value] of Object.entries(obj)) {

            if (obj[key] !== null || obj[key] !== "" || obj[key] !== undefined) {
                //   let key = capitalizeFirstLetter(key);
                title += `${(key.replace('_', ' ')).replace(/\b\w/g, l => l.toUpperCase())}: ${value}` + '/';
            }
        }
        return title;

    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page_value = $(this).attr('href').split('page=')[1];
        var filtered_params = productsFilterParams();
        filtered_params.page = page_value;
        ajaxForGettingResults(filtered_params);
    });


    $('#reset-vendor').on('click',function (e) {
        e.preventDefault();
        $('#vendor').val(null).trigger('change');
    });

    $('#reset-product').on('click',function (e) {
        e.preventDefault();
        $('#product').val(null).trigger('change');
    });

    $('#download-excel').on('click',function (e){
        e.preventDefault();
        var filterd_params = productsFilterParams();
        filterd_params.download_excel = true;
        var queryString = $.param(filterd_params)
        var url = "{{ route('admin.wh-stock-report.index') }}"+'?'+queryString;
        window.open(url,'_blank');
    });

</script>
