<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 25px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #F21805;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider {
        background-color: #50C443;
    }
    input:focus + .slider {
        box-shadow: 0 0 1px #50C443;
    }
    input:checked + .slider:before {
        -webkit-transform: translateX(35px);
        -ms-transform: translateX(35px);
        transform: translateX(35px);
    }
    /* Rounded sliders */
    .slider.round {
        border-radius: 25px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
</style>
<div class="container-fluid">
   <div class="row">
       <div class="col-12">
           <table class="table table-bordered table-striped" cellspacing="0" width="100%">
               <thead>
               <tr>
                   <th>Product</th>
                   <th>Mrp</th>
                   <th>Admin Margin</th>
                   <th>Wholesale Margin</th>
                   <th>Retail Margin</th>
                   <th>Status</th>
               </tr>
               </thead>
               <tbody>

               @forelse($warehousePreOrderProducts as $warehousePreOrderProduct)
                   @php
                       $productAddedToPreOrder = false;
                   @endphp
                   @if($warehousePreOrderProduct->warehouse_preorder_product_code)
                       @php
                           $productAddedToPreOrder = true;
                       @endphp
                   @endif
                       <tr>
                          <td>
                              <h5> {{$warehousePreOrderProduct->product_name}}</h5>
                              <small>{{$warehousePreOrderProduct->product_variant_name}}</small>

                              <div class="row">
                                  <div class="col-sm-12">
                                      <ul>
                                          @foreach($warehousePreOrderProduct->packaging_info as $packagingInfo)
                                              <li>{{$packagingInfo}}</li>
                                          @endforeach
                                      </ul>
                                  </div>
                              </div>
                          </td>
                           <td>
                               {{$warehousePreOrderProduct->mrp}}
                           </td>
                           <td>
                               @if($productAddedToPreOrder)
                                   {{$warehousePreOrderProduct->admin_margin_value}}:{{$warehousePreOrderProduct->admin_margin_type}}
                               @endif

                           </td>
                           <td>
                               @if($productAddedToPreOrder)
                                   {{$warehousePreOrderProduct->wholesale_margin_value}}:{{$warehousePreOrderProduct->wholesale_margin_type}}
                               @endif

                           </td>
                           <td>
                               @if($productAddedToPreOrder)
                                   {{$warehousePreOrderProduct->retail_margin_value}}:{{$warehousePreOrderProduct->retail_margin_type}}
                               @endif

                           </td>
                           <td>
                               @if($warehousePreOrderProduct->warehouse_preorder_product_code)
                                   @if($warehousePreOrderProduct->is_active == 1)
                                       @php
                                           $activeStatus = 'Deactivate';
                                       @endphp

                                           <a href="{{route('warehouse.warehouse-pre-orders.product.toggle-status',
                                                   [
                                                       'warehousePreOrderCode'=>$warehousePreOrderProduct->warehouse_preorder_listing_code,
                                                       'preOrderProductCode'=>$warehousePreOrderProduct->warehouse_preorder_product_code
                                                   ]
                                                )}}" class="toggle-status">
                                               <label class="switch">
                                                   <input type="checkbox" class="change-status-variant" value="on" checked>
                                                   <span class="slider round"></span>
                                               </label>
                                           </a>
                                       @else
                                           @php
                                               $activeStatus = 'Activate';
                                           @endphp

                                            @can('Edit Product Variant Price of Pre Order')
                                               <a href="{{route('warehouse.warehouse-pre-orders.product.toggle-status',
                                                       [
                                                           'warehousePreOrderCode'=>$warehousePreOrderProduct->warehouse_preorder_listing_code,
                                                           'preOrderProductCode'=>$warehousePreOrderProduct->warehouse_preorder_product_code
                                                       ]
                                                    )}}" class="toggle-status">
                                                   <label class="switch">
                                                       <input type="checkbox" value="off" class="change-status-variant">
                                                       <span class="slider round"></span>
                                                   </label>
                                               </a>
                                            @endcan
                                       @endif
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
<script>

    $(document).ready(function (){
        $('.change-status-variant').on('change',function (event){
            event.preventDefault();
            let current = $(this).val();
            Swal.fire({
                title: 'Do you Want To Change Status?',
                showCancelButton: true,
                confirmButtonText: `Change`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = $(this).closest('a').attr('href');

                } else {
                    if (current === 'on') {
                        $(this).prop('checked', true);
                    } else if (current === 'off') {
                        $(this).prop('checked', false);
                    }
                }
            });
        });
    });
</script>



