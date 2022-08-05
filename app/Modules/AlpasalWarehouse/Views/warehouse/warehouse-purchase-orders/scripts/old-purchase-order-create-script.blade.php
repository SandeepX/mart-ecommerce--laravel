<script>
    $(document).ready(function () {

        var submitType = '';
        var productFormData={};
        var productsVariantDetail=[];
        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


        function displayErrorMessage(data) {
            var flashMessage = $('#showFlashMessage');
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            if (data.status == 500) {
                flashMessage.html(closeButton + data.responseJSON.errors);

            }
            if (data.status == 400 || data.status == 419) {
                flashMessage.html(closeButton + data.responseJSON.message);

            }
            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            }
        }

        //empty purchase order table on vendor change
        $("#vendor_code").on('change', function (e) {
            e.preventDefault();
            var productOrderListTbl =$("#product-order-list-tbl-body");//inside tbody
            var rowsLength =productOrderListTbl.children().length;

            if(rowsLength > 0){
                if (confirm("Warning : Changing Vendor deletes the product order list ! ")) {
                    productOrderListTbl.children().remove();
                }
            }


        });

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
            // var productSearchData = new FormData(this);
            // var productSearchData=$(this).serialize();
            var productSearchData=$(this).serializeArray();
            productFormData={};//resetting the object
            productFormData= serializeFormDataToObject(productSearchData);
            /* productSearchData.forEach(function(field) {
                 productFormData[field.name] = field.value;
             });*/
            delete productFormData.page; //deleting page query string

            loadVendorProductsAjax(productFormData);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageNumber=$(this).attr('href').split('page=')[1];
            productFormData['page'] =pageNumber;
            loadVendorProductsAjax(productFormData);
        });

        //populates product filter list table
        function loadVendorProductsAjax(formData) {

            $.ajax({
                type: 'GET',
                url: "/api/admin/filter-products/vendor",
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

        /*********************script for purchase order list table********************/
        $('.save_purchase_order').click(function () {
            // e.preventDefault();
            submitType = $(this).val();

        });

        $("#warehouse-purchase-order-form").on("submit", function (event) {
            event.preventDefault();
            var purchaseOrderData = new FormData(this);
            var vendorCode=$("#vendor_code").val();
            purchaseOrderData.set('vendor_code',vendorCode);
            purchaseOrderData.set('submit_type',submitType);
            //console.log(vendorCode);
            if (submitType == 'sent' ) {
                Swal.fire({
                    title: 'Are you sure you want to place order ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10em',
                    width:'500px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitWarehousePurchaseOrder(purchaseOrderData);
                    }
                })

            }
        });

        function submitWarehousePurchaseOrder(purchaseOrderData) {
            $.ajaxSetup({
                headers:
                    { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
            });

            $.ajax({
                url: "{{ route('warehouse.warehouse-purchase-orders.store') }}",
                method: "POST",
                data: purchaseOrderData,
                datatype: "JSON",
                contentType : false,
                cache : false,
                processData: false
            }).done(function(data) {
                window.location.reload();
                //$("#warehouse_form")[0].reset();
                //$( ".select2" ).val('').trigger('change');
                /* $('#showFlashMessage').removeClass().addClass('alert alert-success').show().empty().html(
                     closeButton + data
                         .message);*/


            }).fail(function(data) {
                displayErrorMessage(data)
            });

        }

        //add to purchase order list table
        $(document).on('click','.add-to-cart-btn',function (e) {
            e.preventDefault();
            var productName = $(this).attr('data-product-name');
            var productCode = $(this).attr('data-product-code');
            loadProductVariantsAjax(productCode);

        });

        //populates purchase order list table
        function loadProductVariantsAjax(productCode) {

            var closeButton =
                '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


            $.ajax({
                type: 'GET',
                url: "/api/admin/filter-product-variants/product/"+productCode,
                //data: formData,
                // dataType: 'html',
            }).done(function(response) {
                // productsVariantDetail.push(response);
                //productsVariantDetail[response.product_]
                insertNewRowInProductOrderListTable(response);//response=productDetail
                $('#showFlashMessage').removeClass().empty();


            }).fail(function(data) {
                displayErrorMessage(data);
            });

        }

        //for deleting row purchase order list table
        $("#product-order-list-tbl").on('click', '.delete-order-btn', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    var closestTr= $(this).closest('tr');
                    productsVariantDetail.splice(closestTr.index(),1); //removing current index product variant detail
                    closestTr.remove();
                    //console.log(productsVariantDetail);
                }
            })

        });

        //calculate price on variant change
        $(document).on('change','select.select2_product_variant',function (e) {

            console.log(1);
            var closestTr= $(this).closest('tr');//getting afno row
            var currentIndex = closestTr.index();

            var quantity =closestTr.find('.quantity-input').val();
            var packageCodeSelectOption =closestTr.find('.select2_package_code');
            var price =$(this).find(':selected').attr('data-price');

            var productVariantCode = $(this).val();
            var productCode = $(this).find(':selected').attr('data-product-code');

            if(price){
                var priceTd =closestTr.find('td.price-td');
                priceTd.html(price);
            }

            if(quantity && price){
                var amountTd =closestTr.find('td.amount-td');
                amountTd.html(quantity*price);
            }

            $.ajax({
                type: 'GET',
                url: `/api/admin/product-packaging-types/${productCode}/${productVariantCode}`,
                //data: formData,
                // dataType: 'html',
            }).done(function(response) {
                productPackagingTypes = response;
                packageCodeSelectOption.empty();
                productPackagingTypes.forEach(function(packageType) {
                    var option = new Option(packageType.package_name,packageType.package_code);
                    //$(option).html(transit.name);
                    //option.setAttribute('data-price',variant.price);
                    // option.setAttribute('value',variant.price);
                    packageCodeSelectOption.append(option);
                });

                $('#showFlashMessage').removeClass().empty();


            }).fail(function(data) {
                displayErrorMessage(data);
            });
        });

        //calculate price with quantity
        $(document).on('input','.quantity-input',function (e) {
            e.preventDefault();

            var quantity = $(this).val();
            var closestTr= $(this).closest('tr');//getting afno row
            var currentIndex = closestTr.index();

            var amountTd =closestTr.find('td.amount-td');
            var variantSelectInput=closestTr.find('select.select2_product_variant');

            if(quantity){
                if(quantity == 0){
                    $(this).val(1);
                    quantity=1;
                }
                var variantDetail = productsVariantDetail[currentIndex];
                if(variantDetail['has_variants']){
                    if(variantSelectInput.val()){ //if variant selected
                        var price =variantSelectInput.find(':selected').attr('data-price');
                        amountTd.html(quantity*price);
                    }
                    else{
                        alert('please select variant first');
                    }

                }
                else{
                    amountTd.html(quantity*variantDetail['price']);
                }
            }
            else{
                amountTd.html('-');
            }

            // console.log(currentIndex);
            // console.log(productsVariantDetail[currentIndex]);

        });

        function insertNewRowInProductOrderListTable(productDetail) {

            var productName=productDetail.product_name;
            var productCode=productDetail.product_code;
            //console.log(productCode);
            var productVariants = productDetail.product_variants;
            var productPrice = productDetail.price;

            var productPackagingTypes = productDetail.product_packaging_types;
            if(productDetail.has_variants){
                productPrice='-';
                productPackagingTypes={};
            }
            var productHasVariants = productDetail.has_variants;

            var productOrderListTable =document.getElementById("product-order-list-tbl-body");
            // Create an empty <tr> element and add it to the last position of the table:
            // console.log(productOrderListTable.rows.length);
            var newRow = productOrderListTable.insertRow(-1);

            currentRowIndex = newRow.rowIndex;
            productsVariantDetail[currentRowIndex-1] = productDetail;
            console.log(productsVariantDetail);
            //Insert a cell in the row at index 0
            var productCell = newRow.insertCell();
            // Append a text node to the cell
            var productCellText = document.createTextNode(productName);
            var productCellInputContent= '<input style="width:75px" name="product_code[]" type="hidden" value='+productCode+'>';
            productCell.appendChild(productCellText);
            productCell.insertAdjacentHTML('beforeend',productCellInputContent);

            var variantInputCell =newRow.insertCell();
            variantInputCell.setAttribute('style','width: 20%');
            var variantInputContent= document.createElement('SELECT');
            //var selectName ='product_variant_code[';
            //var fullSelectName=selectName.concat(productCode,'][]');
            var fullSelectName = 'product_variant_code[]'
            variantInputContent.setAttribute('name',fullSelectName);
            variantInputContent.setAttribute('class','select2_product_variant form-control');
            variantInputCell.appendChild(variantInputContent);

            var packageCodeInputCell =newRow.insertCell();
            packageCodeInputCell.setAttribute('style','width: 20%');
            var packageCodeInputContent= document.createElement('SELECT');
            //var selectName ='product_variant_code[';
            //var fullSelectName=selectName.concat(productCode,'][]');
            var packageCodeSelectName = 'package_code[]'
            packageCodeInputContent.setAttribute('name',packageCodeSelectName);
            packageCodeInputContent.setAttribute('class','select2_package_code form-control');
            packageCodeInputCell.appendChild(packageCodeInputContent);

            if(productHasVariants){
                variantInputContent.insertAdjacentHTML('afterbegin','<option value="" selected readonly>Select Variant</option>');
                //variantInputContent.setAttribute('required',true);
                productVariants.forEach(function(variant) {
                    var option = new Option(variant.product_variant_name,variant.product_variant_code);
                    //$(option).html(transit.name);
                    option.setAttribute('data-price',variant.price);
                    option.setAttribute('data-product-code',productCode);
                    // option.setAttribute('value',variant.price);
                    variantInputContent.append(option);
                });
                packageCodeInputContent.insertAdjacentHTML('afterbegin','<option value="" selected readonly>Select Variant First</option>');
            }
            else{
                // variantInputContent.setAttribute('disabled',true);
                variantInputContent.insertAdjacentHTML('afterbegin','<option value="" selected readonly>No Variant</option>');

                productPackagingTypes.forEach(function(packageType) {
                    var option = new Option(packageType.package_name,packageType.package_code);
                    //$(option).html(transit.name);
                    //option.setAttribute('data-price',variant.price);
                    // option.setAttribute('value',variant.price);
                    packageCodeInputContent.append(option);
                });
            }


            var qtyInputCell =newRow.insertCell();
            var cellContent= '<input class="quantity-input" style="width:75px" min="1" required name="quantity[]" type="number" value="1">';
            qtyInputCell.insertAdjacentHTML('afterbegin',cellContent);

            var priceCell =newRow.insertCell();
            var priceCellContent= document.createTextNode(productPrice);
            priceCell.setAttribute('class','price-td');
            priceCell.appendChild(priceCellContent);

            var amountInputCell =newRow.insertCell();
            var amountInputCellContent= document.createTextNode('-');
            amountInputCell.setAttribute('class','amount-td');
            amountInputCell.appendChild(amountInputCellContent);

            var actionCell =newRow.insertCell();
            var actionCellContent= ' <button class="btn btn-sm btn-danger delete-order-btn">'+
                ' <i class="fa fa-trash"></i>'+
                ' </button>';
            actionCell.insertAdjacentHTML('afterbegin',actionCellContent);
        }
    });
</script>
