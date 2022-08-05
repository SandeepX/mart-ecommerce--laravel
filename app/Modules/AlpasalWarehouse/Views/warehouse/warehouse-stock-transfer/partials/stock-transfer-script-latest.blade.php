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
                insertRowInModal(responseValue)
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

                    insertNewObjectOnResponseArray(response)

                    let checkIfValueIsAvailable = responseData.some(data => data['wpm_code'] === warehouseProductMasterCode);
                    let responseValue = responseData.find(data => data['wpm_code'] === warehouseProductMasterCode);
                    if (checkIfValueIsAvailable) {
                        insertRowInModal(responseValue)
                    } else {
                        insertRowInModal(response)
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
                    if (checkIfExistingWpmPackageData) {
                        if (checkIfExistingResponseData) {
                            let addableStock = existingWpmPackageData.quantity * existingWpmPackageData.micro_qty;
                            existingResponseData.stock = existingResponseData.stock + addableStock;
                            WPMPackageData.splice(existingWpmPackageDataIndex, 1);
                            let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                            changeMaxStockValue(existingResponseData.stock, rowsOFWPM)
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
                if (checkIfExistingWpmPackageData) {
                    if (checkIfExistingResponseData) {
                        let addableStock = existingWpmPackageData.quantity * existingWpmPackageData.micro_qty;
                        existingResponseData.stock = existingResponseData.stock + addableStock;
                        WPMPackageData.splice(existingWpmPackageDataIndex, 1);
                        let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                        changeMaxStockValue(existingResponseData.stock, rowsOFWPM)
                    }
                }
                $this.closest('tr').remove();
                existingResponseData.stock = existingResponseData.stock;
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

    function insertRowInModal(productDetail) {
        var productName = productDetail.product_name;
        var productVariantName = productDetail.product_varient_name;
        var productStock = productDetail.stock;
        if (productStock > 0) {
            $('#package-list').html('')
            $('#choose-package-title').html('Choose Package for: ' + productName + ' (' + productVariantName + ')')
            var productPackagingTypes = productDetail.package_details;
            var productTransferListTable = document.getElementById("package-list");
            var newRow = productTransferListTable.insertRow(-1);
            // newRow.setAttribute('data-id', id)
            // newRow.setAttribute('data-wpm', productDetail.wpm_code)
            currentRowIndex = newRow.rowIndex;

            var packageCodeInputCell = newRow.insertCell();
            packageCodeInputCell.setAttribute('style', 'width: 20%');
            var packageCodeInputContent = document.createElement('SELECT');
            var packageCodeSelectName = 'package_code[]'
            packageCodeInputContent.setAttribute('name', packageCodeSelectName);
            packageCodeInputContent.setAttribute('data-wpm', productDetail.wpm_code);
            packageCodeInputContent.setAttribute('data-id', generateRandomString());
            packageCodeInputContent.setAttribute('class', 'select2_package_code form-control package-row');
            packageCodeInputCell.appendChild(packageCodeInputContent);

            packageCodeInputContent.insertAdjacentHTML('afterbegin', '<option value="" selected readonly disabled>Select Package Type</option>');
            productPackagingTypes.forEach(function (packageType) {
                var option = new Option(packageType.package_name, packageType.package_code);
                option.setAttribute('data-price', packageType.unit_rate);
                packageCodeInputContent.append(option);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'The Selected Product is Out Of Stock',
            }).then((result) => {
                $("#stockTransferModal").modal('hide');
                return false;
            })
        }
    }

    $(document).on('click', '.add-product-to-list', function (e) {
        var currentId = $('.package-row').attr('data-id');
        var wpmCode = $('.package-row').attr('data-wpm');
        var packageCode = $(".package-row option:selected").val();
        let responseValue = responseData.find(data => data['wpm_code'] === wpmCode);
        let checkIfPackageExists = WPMPackageData.some(data => data['wpm_code'] === wpmCode && data['package_code'] === packageCode)
        if (checkIfPackageExists) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'The Selected Product Package is already Exist!',
            }).then((result) => {
                return false;
            })
        } else {
            let packageDetails = responseValue.package_details;
            let respectivePackage = packageDetails.find(data => data['package_code'] === packageCode)
            //let currentStock = responseValue.stock;
            let applicableQty = Math.floor(responseValue.stock / respectivePackage.micro_qty);
            if (applicableQty > 0) {
                responseValue.stock = responseValue.stock - (1 * respectivePackage.micro_qty)
                let wpmObj = {
                    'wpm_code': responseValue.wpm_code,
                    'stock': responseValue.stock,
                    'product_code': responseValue.product_code,
                    'product_name': responseValue.product_name,
                    'product_variant_code': responseValue.product_variant_code,
                    'product_varient_name': responseValue.product_varient_name,
                };
                let wpmPackageInfoObj = {
                    'package_code': packageCode,
                    'max_stock': applicableQty,
                    'quantity': 1,
                    'micro_qty': respectivePackage.micro_qty,
                    'package_name': respectivePackage.package_name,
                    'unit_rate': respectivePackage.unit_rate,
                    'current_id': currentId,
                };
                WPMPackageData.push(Object.assign(wpmObj, wpmPackageInfoObj));

                let respectiveWPMPackage = WPMPackageData.find(data => data['wpm_code'] === wpmCode && data['package_code'] === packageCode);
                let neighbourPackagesOfWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode)
                insertNewRowInProductTransferListTable(respectiveWPMPackage)
                changeMaxStockValue(responseValue.stock, neighbourPackagesOfWPM)
                $("#stockTransferModal").modal('hide');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'The Selected Product Package is Out Of Stock!',
                }).then((result) => {
                    return false;
                })
            }

        }

        // console.log(WPMPackageData)
        //console.log(responseData)

    });

    function changeMaxStockValue(currentStock, neighbourPackagesOfWPM) {
        for (let i = 0; i < neighbourPackagesOfWPM.length; i++) {
            let respectiveQty = neighbourPackagesOfWPM[i].quantity;
            let maxStock = Math.floor((currentStock + (respectiveQty * neighbourPackagesOfWPM[i].micro_qty)) / neighbourPackagesOfWPM[i].micro_qty);
            console.log(maxStock)
            $('.stock-td-'+ neighbourPackagesOfWPM[i].current_id).html('Stock:'+maxStock);
            //console.log($('.stock-td' + neighbourPackagesOfWPM[i].current_id).html(maxStock))
            $('.' + neighbourPackagesOfWPM[i].current_id).attr('max', maxStock);
        }
    }

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

    {{--$('#save_products_as_draft').click(function (e) {--}}
    {{--    e.stopPropagation();--}}
    {{--    e.preventDefault(); // avoid to execute the actual submit of the form.--}}
    {{--    let draftFormData = new FormData(document.getElementById('product-save-as-draft-form'));--}}
    {{--    $.ajaxSetup({--}}
    {{--        headers:--}}
    {{--            {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}--}}
    {{--    });--}}
    {{--    $.ajax({--}}
    {{--        type: "POST",--}}
    {{--        url: "{{route($base_route.'.add-products-stock-transfer-details-draft', $warehouseStockTransfer->stock_transfer_master_code)}}",--}}
    {{--        data: draftFormData, // serializes the form's elements.--}}
    {{--        datatype: "JSON",--}}
    {{--        contentType: false,--}}
    {{--        cache: false,--}}
    {{--        processData: false--}}
    {{--    }).done(function (response) {--}}
    {{--        alert('Products data successfully as a draft!');--}}
    {{--        location.reload();--}}
    {{--    }).fail(function (data) {--}}
    {{--        displayErrorMessage(data);--}}
    {{--    });--}}
    {{--});--}}

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
        var currentId = closestTr.attr('data-id');
        var amountTd = closestTr.find('td.amount-td');
        var priceTd = closestTr.find('td.price-td');
        var packageCode = closestTr.find('.package-code-data').val();

        if (quantity) {
            if (quantity <= 0) {
                $(this).val(1);
                quantity = 1;
            }
            var maxStockAvailable = $(this).attr('max');
            if (packageCode) { //if package selected
                var wpmCode = closestTr.attr('data-wpm');
                let responseValue = responseData.find(data => data['wpm_code'] === wpmCode);
                let existingRow = WPMPackageData.find(data => data['current_id'] === currentId);
                var price = existingRow.unit_rate;
                if (parseInt(maxStockAvailable) < parseInt(quantity)) {
                    console.log(maxStockAvailable, quantity, 'quantity greater than max stock');
                    $(this).val(maxStockAvailable);
                    quantity = maxStockAvailable;
                }
                let microQty = existingRow.micro_qty;
                let reducableStock = quantity * microQty;
                let additionalStock = existingRow.quantity * microQty;

                let currentStock = (responseValue.stock + additionalStock) - reducableStock;
                if (currentStock >= 0) {
                    responseValue.stock = currentStock;
                    existingRow.quantity = quantity;
                    existingRow.stock = responseValue.stock;
                    existingRow.price = price;
                    existingRow.sub_total = quantity * price;

                    let rowsOFWPM = WPMPackageData.filter(data => data['wpm_code'] === wpmCode);
                    let subTotal = quantity * price;
                    amountTd.html(subTotal.toFixed(2));
                    changeMaxStockValue(responseValue.stock, rowsOFWPM)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The Selected Product is Out Of Stock!!!',
                    }).then((result) => {

                        return false;
                    })
                }
            }
        }

    });

    function insertNewObjectOnResponseArray(response) {
        if (checkDuplicateData(response)) {
            responseData.push(response);
        }
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
        var microQty = productDetail.micro_qty;
        var id = productDetail.current_id;

        var productTransferListTable = document.getElementById("selected_products_tbl");

        var newRow = productTransferListTable.insertRow(-1);
        newRow.setAttribute('data-id', id)
        newRow.setAttribute('data-wpm', productDetail.wpm_code)
        currentRowIndex = newRow.rowIndex;

        var productCell = newRow.insertCell();
        // Append a text node to the cell
        var productCellText = document.createTextNode(productName);
        if(productDetail.product_variant_code) {
            var productVariantCellText = document.createTextNode(' (' + productVariantName + ')');
        }
        var productCellInputContent = '<input style="width:75px" name="product_code[]" type="hidden" value=' + productDetail.product_code + '>';
        var productVariantCellInputContent = '<input style="width:75px" name="product_variant_code[]" type="hidden" value=' + productDetail.product_variant_code + '>';
        var wpmCodeCellInputContent = '<input style="width:75px" name="warehouse_product_master_code[]" type="hidden" value=' + productDetail.wpm_code + '>';
        productCell.appendChild(productCellText);
        productCell.appendChild(productVariantCellText);
        productCell.insertAdjacentHTML('beforeend', productCellInputContent);
        productCell.insertAdjacentHTML('beforeend', productVariantCellInputContent);
        productCell.insertAdjacentHTML('beforeend', wpmCodeCellInputContent);

        // var productVariantCell = newRow.insertCell();
        // var productVariantCellInputContent = '<input style="width:75px" name="product_variant_code[]" type="hidden" value=' + productDetail.product_variant_code + '>';
        // productVariantCell.insertAdjacentHTML('beforeend', productVariantCellInputContent);

        let maxStock = Math.floor(productStock / microQty);
        var packageInputCell = newRow.insertCell();
        var packageCellText = document.createTextNode(productDetail.package_name);
        var packageInputCellContent = '<input style="width:75px" class="package-code-data" name="package_code[]" type="hidden" value=' + productDetail.package_code + '>';
        let brEl ='<br>'
        let stockCellContent = '<span class="stock-td-'+id+'">Max Stock: '+maxStock+'<span>'
        packageInputCell.setAttribute('class', 'package-data');
        packageInputCell.appendChild(packageCellText);
        packageInputCell.insertAdjacentHTML('beforeend', packageInputCellContent);
        packageInputCell.insertAdjacentHTML('beforeend', brEl);
        packageInputCell.insertAdjacentHTML('beforeend', stockCellContent);

        // var stockCell = newRow.insertCell();
        // var stockCellContent = document.createTextNode(Math.floor(productStock / microQty));
        // stockCell.setAttribute('class', 'stock-td' + id);
        // stockCell.appendChild(stockCellContent);

        var qtyInputCell = newRow.insertCell();
        var cellContent = '<input class="quantity-input ' + id + '"  style="width:75px" max="" required name="quantity[]" type="number" value="' + productDetail.quantity + '">';
        qtyInputCell.insertAdjacentHTML('afterbegin', cellContent);

        var priceCell = newRow.insertCell();
        let unitRate = productDetail.unit_rate;
        var priceCellContent = document.createTextNode(unitRate.toFixed(2));
        priceCell.setAttribute('class', 'price-td');
        priceCell.appendChild(priceCellContent);

        var amountInputCell = newRow.insertCell();
        let subTotal = productDetail.unit_rate * productDetail.quantity;
        var amountInputCellContent = document.createTextNode(subTotal.toFixed(2));
        amountInputCell.setAttribute('class', 'amount-td');
        amountInputCell.appendChild(amountInputCellContent);

        var actionCell = newRow.insertCell();
        var actionCellContent = ' <button class="btn btn-sm btn-danger remove-row">' +
            ' <i class="fa fa-trash"></i>' +
            ' </button>';
        actionCell.insertAdjacentHTML('afterbegin', actionCellContent);
    }
</script>
