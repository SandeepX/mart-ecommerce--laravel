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
            if (submitType == 'sent' && confirm("Are you sure you want to place order ? ")) {
                submitWarehousePurchaseOrder(purchaseOrderData);
            }else {
                submitWarehousePurchaseOrder(purchaseOrderData);
            }

        });

        function submitWarehousePurchaseOrder(purchaseOrderData) {
            $.ajaxSetup({
                headers:
                    { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
            });

            $.ajax({
                url: "{{ route('warehouse.warehouse-purchase-orders') }}",
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
            if (confirm("Are you sure you want to delete ? ")) {
                var closestTr= $(this).closest('tr');
                productsVariantDetail.splice(closestTr.index(),1); //removing current index product variant detail
                closestTr.remove();
                //console.log(productsVariantDetail);
            }

        });

        //calculate price on variant change
        $(document).on('change','select.select2_product_variant',function (e) {

            var closestTr= $(this).closest('tr');//getting afno row
            var currentIndex = closestTr.index();

            var quantity =closestTr.find('.quantity-input').val();
            var price =$(this).find(':selected').attr('data-price');

            if(price){
                var priceTd =closestTr.find('td.price-td');
                priceTd.html(price);
            }

            if(quantity && price){
                var amountTd =closestTr.find('td.amount-td');
                amountTd.html(quantity*price);
            }
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
            console.log(productCode);
            var productVariants = productDetail.product_variants;
            var productPrice = productDetail.price;
            if(productDetail.has_variants){
                productPrice='-';
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
            var selectName ='product_variant_code[';
            var fullSelectName=selectName.concat(productCode,'][]');
            variantInputContent.setAttribute('name',fullSelectName);
            variantInputContent.setAttribute('class','select2_product_variant form-control');
            variantInputCell.appendChild(variantInputContent);
            variantInputContent.insertAdjacentHTML('afterbegin','<option value="" selected readonly>Select Variant</option>');

            if(productHasVariants){
                //variantInputContent.setAttribute('required',true);
                productVariants.forEach(function(variant) {
                    var option = new Option(variant.product_variant_name,variant.product_variant_code);
                    //$(option).html(transit.name);
                    option.setAttribute('data-price',variant.price);
                   // option.setAttribute('value',variant.price);
                    variantInputContent.append(option);
                });
            }
            else{
                variantInputContent.setAttribute('disabled',true);
            }


            var qtyInputCell =newRow.insertCell();
            var cellContent= '<input class="quantity-input" style="width:75px" min="1" required name="quantity[]" type="number" value="">';
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