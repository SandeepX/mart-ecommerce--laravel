<script type="text/javascript">
    let responseData = [];
    let input = [];
    let WPMPackageData = [];
    let WPMCode;
    let previousIndex;
    let previousPackage;
    let previousQty;
    let OutOfStockWPM = [];
    $(function () {
        let formProductData = {};
        $('body').on('click', '.product_class', function () {

            $.ajaxSetup({
                headers: {"X-Test-Header": "test-value"}
            })
            let warehouseProductMasterCode = $(this).data('warehouse_product_master_code');
            WPMCode = warehouseProductMasterCode;
            let checkIfValueIsAvailable = responseData.some(data => data['wpm_code'] === warehouseProductMasterCode);
            let responseValue = responseData.find(data => data['wpm_code'] === warehouseProductMasterCode);
            if (checkIfValueIsAvailable) {
                insertNewRowInProductTransferListTable(responseValue);
            } else {
                $.ajax({

                    {{--url: '{{ route($base_route.".add-products-table", $stockTransferCode) }}',--}}
                    url: '{{ route("warehouse.wh-product-lists", $stockTransferCode) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        warehouse_product_master_code: $(this).data('warehouse_product_master_code')
                    },
                }).done(function (response) {
                    // $('#selected_products_tbl').append(response.html);
                    // responseData.push(response);
                    insertNewObjectOnResponseArray(response)
                    //  console.log(WPMCode)
                    let checkIfValueIsAvailable = responseData.some(data => data['wpm_code'] === warehouseProductMasterCode);
                    let responseValue = responseData.find(data => data['wpm_code'] === warehouseProductMasterCode);
                    if (checkIfValueIsAvailable) {
                        insertNewRowInProductTransferListTable(responseValue);
                    } else {
                        insertNewRowInProductTransferListTable(response);
                    }

                    $('#showFlashMessage').removeClass().empty();
                }).fail(function (data) {
                    displayErrorMessage(data);
                });
            }
            // console.log(warehouseProductMasterCode)

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
                    let rowId = $this.closest('tr').attr('data-id');
                    let wpmCode = $this.closest('tr').attr('data-wpm');

                    let existingResponseData = responseData.find(data => data['wpm_code'] === wpmCode);
                    let checkIfExistingResponseData = responseData.some(data => data['wpm_code'] === wpmCode);
                    let existingWpmPackageData = WPMPackageData.find(data => data['current_id'] === rowId);
                    let existingWpmPackageDataIndex = WPMPackageData.findIndex(data => data['current_id'] === rowId);
                    let checkIfExistingWpmPackageData = WPMPackageData.some(data => data['current_id'] === rowId);
                    if(checkIfExistingWpmPackageData)
                    {
                        if(checkIfExistingResponseData)
                        {
                            let addableStock = existingWpmPackageData.quantity * existingWpmPackageData.micro_qty;
                            existingResponseData.stock = existingResponseData.stock + addableStock;
                            WPMPackageData.splice(existingWpmPackageDataIndex,1);
                            let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                            //console.log(rowsOFWPM, responseValue.stock);
                            for (let i = 0; i < rowsOFWPM.length; i++) {
                                // console.log(respectiveQty)
                                let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                                let transferableStock = ((existingResponseData.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                                $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                                $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);

                            }
                        }
                    }
                    $this.closest('tr').remove();
                }).fail(function (data) {
                    displayErrorMessage(data);
                });
            } else {
                let rowId = $this.closest('tr').attr('data-id');
                let wpmCode = $this.closest('tr').attr('data-wpm');

                let existingResponseData = responseData.find(data => data['wpm_code'] === wpmCode);
                let checkIfExistingResponseData = responseData.some(data => data['wpm_code'] === wpmCode);
                let existingWpmPackageData = WPMPackageData.find(data => data['current_id'] === rowId);
                let existingWpmPackageDataIndex = WPMPackageData.findIndex(data => data['current_id'] === rowId);
                let checkIfExistingWpmPackageData = WPMPackageData.some(data => data['current_id'] === rowId);
                if(checkIfExistingWpmPackageData)
                {
                    if(checkIfExistingResponseData)
                    {
                        let addableStock = existingWpmPackageData.quantity * existingWpmPackageData.micro_qty;
                        console.log(addableStock,'addable stock')
                        existingResponseData.stock = existingResponseData.stock + addableStock;
                        console.log(existingResponseData,'existing responnse data')
                        WPMPackageData.splice(existingWpmPackageDataIndex,1);
                        let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                        //console.log(rowsOFWPM, existingResponseData.stock);
                        for (let i = 0; i < rowsOFWPM.length; i++) {
                            let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                            let transferableStock = ((existingResponseData.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                            $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                            $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);

                        }
                    }
                }
                $this.closest('tr').remove();
            }
            console.log(responseData,'remove row')
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

    $(document).on('focus', 'select.select2_package_code', function (e) {
        previousIndex = $(this).find(':selected').index();
        previousPackage = $(this).val();
        // console.log(previousPackage,'previous package')
    });
    $(document).on('focus', '.quantity-input', function (e) {
        previousQty = $(this).val();
        // console.log(previousPackage,'previous package')
    });
    //calculate price on package change
    $(document).on('change', 'select.select2_package_code', function (e) {
        var closestTr = $(this).closest('tr');//getting afno row
        var currentIndex = closestTr.index();
        var currentId = closestTr.attr('data-id');
        var wpmCode = closestTr.attr('data-wpm');
        let responseValue = responseData.find(data => data['wpm_code'] === wpmCode);
        var packageCode = $(this).find(':selected').val();
        var id = $(this).attr('id');

        //  responseValue.package_details.splice(index-1,1);
        // console.log(responseData)

        //    changing affected data in object

        let existingRow = WPMPackageData.find(data => data['current_id'] === currentId);
        let checkIfIdExist = WPMPackageData.some(data => data['current_id'] === currentId);

        if (checkIfPackageExist(wpmCode, packageCode)) {
            var quantity = closestTr.find('.quantity-input').val();
            if (quantity == '' || quantity <= 0) {
                closestTr.find('.quantity-input').val(1);
                quantity = 1;
            }
            var price = $(this).find(':selected').attr('data-price');
            if (price) {
                var priceTd = closestTr.find('td.price-td');
                priceTd.html(price);
            }

            if (quantity && price) {
                var amountTd = closestTr.find('td.amount-td');
                amountTd.html(quantity * price);
            }
            let packageDetail = responseValue.package_details.find(data => data['package_code'] === packageCode);
            let microQty = packageDetail.micro_qty;
            let reducableStock = quantity * microQty;
            if (checkIfIdExist) {
                let additionalStock = existingRow.quantity * existingRow.micro_qty;
                let maxQtyForExisting = responseValue.stock / microQty;

                //maxStock.html(maxQtyForExisting);
                //$('.stock-td'+wpmCode).html(maxQtyForExisting)
                // console.log((responseValue.stock + additionalStock) - reducableStock)
               // console.log('wpmpackage data',WPMPackageData)
                let WPMPackageOfCurrentId = WPMPackageData.find(data=>data['current_id'] === currentId);
               // console.log(WPMPackageOfCurrentId,'above')
                let IfWPMPackageOutOfStockExist = WPMPackageData.some(data=>data['out_of_stock'] === 1 && data['current_id'] === currentId);
                let currentStock = 0;
                if(IfWPMPackageOutOfStockExist)
                {
                     currentStock = responseValue.stock - reducableStock;
                }
                else{
                     currentStock = (responseValue.stock + additionalStock) - reducableStock;
                }
                let applicableQty = 0;
                 if(currentStock < 0)
                 {
                     if(IfWPMPackageOutOfStockExist)
                     {
                         currentStock = currentStock;
                     }
                     else{
                         currentStock = currentStock + reducableStock;
                     }
                     applicableQty = Math.floor(currentStock/microQty);
                     reducableStock = applicableQty * microQty;
                     currentStock = currentStock - reducableStock;
                     closestTr.find('.quantity-input').val(applicableQty);
                 }

                if (applicableQty > 0) {
                    existingRow.package_code = packageCode;
                    existingRow.stock = currentStock;
                    existingRow.price = price;
                    existingRow.quantity = quantity;
                    existingRow.micro_qty = microQty;
                    existingRow.sub_total = quantity * price;
                    responseValue.stock = currentStock;

                    // let ifRowsOFWPMExists = WPMPackageData.some(data => data['wpm_code'] === wpmCode);
                    // if(ifRowsOFWPMExists)
                    // {
                    let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                    //console.log(rowsOFWPM, responseValue.stock);
                    for (let i = 0; i < rowsOFWPM.length; i++) {
                       // console.log(respectiveQty)
                        let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                        let transferableStock = ((responseValue.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                        $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                        $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);

                    }

                    // }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The Selected Product is Out Of Stock!',
                    }).then((result) => {
                        // let dataId = {
                        //     'current_id' : currentId,
                        //     'wpm_code' : wpmCode,
                        // };
                        // OutOfStockWPM.push(dataId);
                        // let outOfStockId = OutOfStockWPM.filter(data=>data['current_id'] == currentId);
                        // console.log(responseValue.stock,'out of stock existing')
                        //console.log(WPMPackageOfCurrentId,'sdhgsdhgfd')
                        // if(IfWPMPackageOutOfStockExist)
                        // {
                            responseValue.stock = responseValue.stock;

                        // }
                        //  else{
                        //      WPMPackageOfCurrentId.out_of_stock = 1;
                        //     responseValue.stock = responseValue.stock + additionalStock;
                        // }
                        //console.log(responseValue.stock,'--')
                        let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                        let rowOFWPM = WPMPackageData.find(data => data['current_id'] === currentId);
                       // console.log(rowOFWPM, responseValue.stock);
                       // rowsOFWPM['stock'] = responseValue.stock;
                       //  var packageCode = $(this).find(':selected').val();
                       //  let packageDetail = responseValue.package_details.find(data => data['package_code'] === packageCode);
                       // let microQty = packageDetail.micro_qty;
                            for (let i = 0; i < rowsOFWPM.length; i++) {
                                let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                                let transferableStock = ((responseValue.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                                $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                                $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);
                                // $('.stock-td'+rowsOFWPM[i].current_id).html((responseValue.stock) / (rowsOFWPM[i]['micro_qty']))
                                // $('.'+rowsOFWPM[i].current_id).attr('max', (responseValue.stock) / (rowsOFWPM[i]['micro_qty']));
                            }
                        // $('.stock-td'+currentId).html((responseValue.stock + (rowOFWPM.stock * rowOFWPM.micro_qty)) / rowOFWPM['micro_qty'])
                        // $('.'+currentId).attr('max', ((responseValue.stock + (rowOFWPM.stock * rowOFWPM.micro_qty)) / rowOFWPM['micro_qty']));
                        //maxStock.html(responseValue.stock + additionalStock)
                        if (checkIfIdExist) {
                            $("#" + id).val(rowOFWPM.package_code)
                            closestTr.find('.quantity-input').val(rowOFWPM.quantity);
                            priceTd.html(rowOFWPM.price)
                            amountTd.html(rowOFWPM.price * rowOFWPM.quantity)
                            // e.target.value(rowOFWPM.package_code);
                            // console.log(e.target.value(rowOFWPM.package_code))
                            //$(this).find(':selected').html(rowOFWPM.package_name);
                        } else {
                            $("#" + id).prop('selectedIndex', 0)
                            closestTr.find('.quantity-input').val('');
                            priceTd.html('-');
                            amountTd.html('-');
                        }


                        //  $('#baba').prop('selectedIndex',0);
                    })
                }
            } else {
                let maxQtyForInitial = responseValue.stock / microQty;
                // console.log(maxQtyForInitial);
                // closestTr.find('.quantity-input').attr('max',maxQtyForInitial);
                // $('.stock-td'+wpmCode).html(maxQtyForInitial)
                let currentStock = responseValue.stock - reducableStock;
                if (currentStock >= 0) {
                    responseValue.stock = currentStock;
                    let wpmObj = {
                        'wpm_code': responseValue.wpm_code,
                        'stock': responseValue.stock,
                        'product_name': responseValue.product_name,
                    };
                    let wpmPackageInfoObj = {
                        'package_code': packageCode,
                        'quantity': quantity,
                        'micro_qty': microQty,
                        'price': price,
                        'sub_total': quantity * price,
                        'current_id': currentId,
                        'out_of_stock': 0,
                    };

                    WPMPackageData.push(Object.assign(wpmObj, wpmPackageInfoObj));
                    let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                        for (let i = 0; i < rowsOFWPM.length; i++) {
                            let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                            let transferableStock = ((responseValue.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                            $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                            $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);
                        }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The Selected Product is Out Of Stock!',
                    }).then((result) => {
                        // let dataId = {
                        //     'current_id' : currentId,
                        //     'wpm_code' : wpmCode,
                        // };
                        // OutOfStockWPM.push(dataId);
                        // let outOfStockId = OutOfStockWPM.filter(data=>data['current_id'] == currentId);
                        // let WPMPackageOfCurrentId = WPMPackageData.find(data=>data['current_id'] === currentId);
                        // let IfWPMPackageOutOfStockExist = WPMPackageData.some(data=>data['out_of_stock'] === 1 && data['current_id'] === currentId);
                        // if(IfWPMPackageOutOfStockExist)
                        // {
                        //     responseValue.stock = responseValue.stock;
                        // }
                        // else{
                        //     WPMPackageOfCurrentId.out_of_stock = 1;
                        //     responseValue.stock = responseValue.stock + reducableStock;
                        //
                        // }
                        if (checkIfIdExist) {
                            $("#" + id).prop('selectedIndex', currentIndex);
                        } else {
                            $("#" + id).prop('selectedIndex', 0)
                        }
                        let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                            for (let i = 0; i < rowsOFWPM.length; i++) {
                                // $('.stock-td'+rowsOFWPM[i].current_id).html((responseValue.stock) / (rowsOFWPM[i]['micro_qty']))
                                // $('.'+rowsOFWPM[i].current_id).attr('max', (responseValue.stock) / (rowsOFWPM[i]['micro_qty']));
                                let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                                let transferableStock = ((responseValue.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                                $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                                $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);
                            }
                        // maxStock.html(currentStock+reducableStock);

                        priceTd.html('-');
                        amountTd.html('-');
                        closestTr.find('.quantity-input').val('');
                        //  $('#baba').prop('selectedIndex',0);
                    })
                }
            }
            // console.log(WPMPackageData)
            //console.log(responseData)
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Package already exists for this Product!',
            }).then((result) => {
                closestTr.find('.quantity-input').attr('max', responseValue.stock);
                // maxStock.html(responseValue.stock);
                if (checkIfIdExist) {
                    $("#" + id).prop('selectedIndex', currentIndex);
                } else {
                    $("#" + id).prop('selectedIndex', 0)
                }

                //  $('#baba').prop('selectedIndex',0);
            })
        }
        // else{
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: 'The Selected Product is Out Of Stock!',
        //     }).then((result) => {
        //         return false;
        //         //  $('#baba').prop('selectedIndex',0);
        //     })
        // }
        // input.push(obj);

         console.log(responseData,'package change')
    });

    function checkIfPackageExist(wpmCode, packageCode) {
        let checkIfRowExist = WPMPackageData.some(data => data['wpm_code'] === wpmCode
            && data['package_code'] === packageCode);
        if (checkIfRowExist) {
            return false;
        } else {
            return true
        }
    }

    function generateRandomString() {
        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }

    //calculate price with quantity
    $(document).on('input', '.quantity-input', function (e) {
        e.preventDefault();

        var quantity = $(this).val();
        var closestTr = $(this).closest('tr');//getting afno row
        var closestTd = $(this).closest('td');//getting afno td
        var currentIndex = closestTr.index();
        var currentId = closestTr.attr('data-id');
        var amountTd = closestTr.find('td.amount-td');
        var priceTd = closestTr.find('td.price-td');
        var packageSelectInput = closestTr.find('select.select2_package_code');

        if (quantity) {
            if (quantity <= 0) {
                $(this).val(1);
                quantity = 1;
            }
            //var variantDetail = productsVariantDetail[currentIndex];
            //if(variantDetail['has_variants']){
            if (packageSelectInput.val()) { //if package selected
                var price = packageSelectInput.find(':selected').attr('data-price');
                amountTd.html(quantity * price);
                var wpmCode = closestTr.attr('data-wpm');
                let responseValue = responseData.find(data => data['wpm_code'] === wpmCode);
                let existingRow = WPMPackageData.find(data => data['current_id'] === currentId);
                var packageCode = closestTr.find(':selected').val();
                // console.log(packageCode);
                let packageDetail = responseValue.package_details.find(data => data['package_code'] === packageCode);
                let microQty = packageDetail.micro_qty;
                let reducableStock = quantity * microQty;
                let additionalStock = existingRow.quantity * existingRow.micro_qty;
                let IfWPMPackageOutOfStockExist = WPMPackageData.some(data=>data['out_of_stock'] === 1 && data['current_id'] === currentId);
                let checkIfIdExist = WPMPackageData.some(data => data['current_id'] === currentId);
                let currentStock = 0;
                if(IfWPMPackageOutOfStockExist)
                {
                    currentStock = responseValue.stock - reducableStock;
                }
                else{
                    currentStock = (responseValue.stock + additionalStock) - reducableStock;
                }
                //let currentStock = (responseValue.stock + additionalStock) - reducableStock;
                //console.log(currentStock)
                if (currentStock >= 0) {
                    responseValue.stock = currentStock;
                    existingRow.quantity = quantity;
                    existingRow.stock = responseValue.stock;
                    existingRow.price = price;
                    existingRow.sub_total = quantity * price;

                    let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                    //console.log(rowsOFWPM, responseValue.stock);
                    for (let i = 0; i < rowsOFWPM.length; i++) {
                        // console.log(respectiveQty)
                        let respectiveQty =  $('.'+rowsOFWPM[i].current_id).val();
                        let transferableStock = ((responseValue.stock + (respectiveQty * rowsOFWPM[i]['micro_qty'])) / (rowsOFWPM[i]['micro_qty']))
                        $('.stock-td'+rowsOFWPM[i].current_id).html(transferableStock)
                        $('.'+rowsOFWPM[i].current_id).attr('max',transferableStock);

                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The Selected Product is Out Of Stock!!!',
                    }).then((result) => {
                        responseValue.stock = currentStock + reducableStock;
                        let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                        let rowOFWPM = WPMPackageData.find(data => data['current_id'] === currentId);
                        //console.log(rowsOFWPM, responseValue.stock);
                        // rowsOFWPM['stock'] = responseValue.stock;
                        //  var packageCode = $(this).find(':selected').val();
                        //  let packageDetail = responseValue.package_details.find(data => data['package_code'] === packageCode);
                        // let microQty = packageDetail.micro_qty;
                        for (let i = 0; i < rowsOFWPM.length; i++) {
                            $('.stock-td'+rowsOFWPM[i].current_id).html((responseValue.stock) / (rowsOFWPM[i]['micro_qty']))
                            $('.'+rowsOFWPM[i].current_id).attr('max', (responseValue.stock) / (rowsOFWPM[i]['micro_qty']));
                        }
                        $('.stock-td'+currentId).html((responseValue.stock) / rowOFWPM['micro_qty'])
                        $('.'+currentId).attr('max', (responseValue.stock) / rowOFWPM['micro_qty']);
                        //maxStock.html(responseValue.stock + additionalStock)
                        if (checkIfIdExist) {
                            $(this).val(rowOFWPM.quantity);
                        } else {
                            $(this).val('');
                            priceTd.html('-');
                            amountTd.html('-');
                        }
                        // for (let i = 0; i < rowsOFWPM.length; i++) {
                        //     $('.stock-td'+rowsOFWPM[i].current_id).html((responseValue.stock) / (rowsOFWPM[i]['micro_qty']))
                        //     $('.'+rowsOFWPM[i].current_id).attr('max', (responseValue.stock) / (rowsOFWPM[i]['micro_qty']));
                        // }
                        // $(this).val('');
                        // priceTd.html('-');
                        // amountTd.html('-');
                        //  $('#baba').prop('selectedIndex',0);
                    })
                }
                // console.log(responseData)
                //console.log(WPMPackageData)
            } else {
                // alert('please select package first');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'please select package first',
                }).then((result) => {
                    $(this).val('');
                })
            }

            // }
            // else{
            //     amountTd.html(quantity*variantDetail['price']);
            // }
        } else {
            amountTd.html('-');
        }
        console.log(responseData,'quantity change')
    });

    function insertNewObjectOnResponseArray(response) {
        if (checkDuplicateData(response)) {
            responseData.push(response);
        }
        //console.log(responseData)
    }

    function checkDuplicateData(response) {

        let checkIfValueIsAvailable = responseData.some(data => data['wpm_code'] === response['wpm_code']);

        if (checkIfValueIsAvailable) {
            return false
        }
        return true
    }

    function insertNewRowInProductTransferListTable(productDetail) {

        var productName = productDetail.product_name;
        var productVariantName = productDetail.product_varient_name;
        var productCode = productDetail.product_code;
        var productStock = productDetail.stock;
        var id = generateRandomString();
        if (productStock > 0) {
            //var productPrice = productDetail.vendor_price;

            var productPackagingTypes = productDetail.package_details;
            var productTransferListTable = document.getElementById("selected_products_tbl");

            var newRow = productTransferListTable.insertRow(-1);
            newRow.setAttribute('data-id', id)
            newRow.setAttribute('data-wpm', productDetail.wpm_code)
            currentRowIndex = newRow.rowIndex;

            var productCell = newRow.insertCell();
            // Append a text node to the cell
            var productCellText = document.createTextNode(productName);
            var productCellInputContent = '<input style="width:75px" name="product_code[]" type="hidden" value=' + productCode + '>';
            productCell.appendChild(productCellText);
            productCell.insertAdjacentHTML('beforeend', productCellInputContent);

            var productVariantCell = newRow.insertCell();
            var productVariantCellText = document.createTextNode(productVariantName);
            productVariantCell.appendChild(productVariantCellText);

            var packageCodeInputCell = newRow.insertCell();
            packageCodeInputCell.setAttribute('style', 'width: 20%');
            var packageCodeInputContent = document.createElement('SELECT');
            var packageCodeSelectName = 'package_code[]'
            packageCodeInputContent.setAttribute('name', packageCodeSelectName);
            packageCodeInputContent.setAttribute('id', generateRandomString());
            packageCodeInputContent.setAttribute('class', 'select2_package_code form-control');
            packageCodeInputCell.appendChild(packageCodeInputContent);

            packageCodeInputContent.insertAdjacentHTML('afterbegin', '<option value="" selected readonly disabled>Select Package Type</option>');
            productPackagingTypes.forEach(function (packageType) {
                var option = new Option(packageType.package_name, packageType.package_code);
                option.setAttribute('data-price', packageType.unit_rate);
                packageCodeInputContent.append(option);
            });
            var stockCell = newRow.insertCell();
            var stockCellContent = document.createTextNode(productStock);
            stockCell.setAttribute('class', 'stock-td' + id);
            stockCell.appendChild(stockCellContent);

            var qtyInputCell = newRow.insertCell();
            var cellContent = '<input class="quantity-input '+id+'"  style="width:75px" max="" required name="quantity[]" type="number" value="">';
            qtyInputCell.insertAdjacentHTML('afterbegin', cellContent);

            var priceCell = newRow.insertCell();
            var priceCellContent = document.createTextNode('-');
            priceCell.setAttribute('class', 'price-td');
            priceCell.appendChild(priceCellContent);

            var amountInputCell = newRow.insertCell();
            var amountInputCellContent = document.createTextNode('-');
            amountInputCell.setAttribute('class', 'amount-td');
            amountInputCell.appendChild(amountInputCellContent);

            var actionCell = newRow.insertCell();
            var actionCellContent = ' <button class="btn btn-sm btn-danger remove-row">' +
                ' <i class="fa fa-trash"></i>' +
                ' </button>';
            actionCell.insertAdjacentHTML('afterbegin', actionCellContent);
        } else {
            // alert('please select package first');
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'The Selected Product is Out Of Stock',
            }).then((result) => {

            })
        }
    }
</script>
