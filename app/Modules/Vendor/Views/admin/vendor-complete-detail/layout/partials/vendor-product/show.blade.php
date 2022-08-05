<style>
    .box-color {
        float: left;
        height: 15px;
        width: 10px;
        padding-top: 5px;
        border: 1px solid black;
    }

    .danger-color {
        background-color:  #ff667a ;
    }

    .warning-color {
        background-color:  #f5c571 ;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
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
        height: 17px;
        width: 16px;
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
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
</style>

<div class="card card-default bg-panel">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-5">
                        <h3 style="margin-left:10px; font-weight: bold;">List of Vendor Products</h3>
                    </div>
                    <div class="col-md-3">
                        <h3 style="font-weight: bold;">{{$products->total() }}</h3>
                        <p>Total Vendor Products</p>
                    </div>

{{--                    <div class="col-md-4">--}}
{{--                        <a style="margin-top: 30px !important;" class="btn btn-danger" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseExample">--}}
{{--                            <i class="fa  fa-filter"></i>--}}
{{--                        </a>                    </div>--}}
{{--                    </div>--}}
            </div>

{{--            <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">--}}
{{--                <div class="panel-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}

{{--                            <form class="vendor_filter_form" action="{{ route('admin.vendor.products',$vendorCode) }}" method="GET">--}}
{{--                                @csrf--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-xs-6">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="product_name">Product</label>--}}
{{--                                            <input type="text" class="form-control" name="product_name" id="product_name"--}}
{{--                                                   value="{{$filterParameters['product_name']}}">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <button type="submit" class="btn btn-block btn-primary form-control filter-vendor-product">Filter</button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}


{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
        <div class="col-xs-12">

            <div class="alert alert-success" id="success" role="alert">
                <p>Vendor product status changed successfully</p>
            </div>

            <div class="alert alert-danger" id="failed" role="alert">
                <p>Vendor product status change failed</p>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        List of Vendor Products
                    </h3>
                </div>

                <div class="box-body">

                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Price</th>
                            <th>Taxable</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $i => $product)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>
                                    <strong>Name </strong> : {{$product->product_name}}<br>
                                    <strong>Brand </strong> : {{$product->brand->brand_name}}<br>
                                    <strong>Category </strong> : {{$product->category->category_name}}<br>
                                    <strong>Vendor </strong> : {{$product->vendor->vendor_name}}
                                </td>
                                <td>{{$product->product_code}}</td>
                                <td>
                                @if(count($product->priceList) > 0)

                                    <!-- Trigger the modal with a button -->
                                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#productPrice{{$i}}">
                                            <i class="fa fa-eye"></i>
                                            Price
                                        </button>

                                        <!-- Modal -->
                                        <div id="productPrice{{$i}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div style="overflow:auto;" class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Product : {{$product->product_name}} </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        @foreach($product->priceList as $price)
                                                            @if($price->productVariant)
                                                                <strong>Variant </strong> : {{$price->productVariant->product_variant_name}}<br>
                                                            @endif
                                                            <strong>MRP </strong> : {{$price->mrp}}<br>
                                                            <strong>Admin Margin </strong> : {{$price->admin_margin_value}} ({{$price->admin_margin_type == "p" ? "%" : "flat" }})<br>
                                                            <strong>Whole Sale Margin </strong> : {{$price->wholesale_margin_value}} ({{$price->wholesale_margin_type == "p" ? "%" : "flat" }})<br>
                                                            <strong>Retail Margin </strong> : {{$price->retail_store_margin_value}} ({{$price->retail_store_margin_type == "p" ? " %" : "flat" }})
                                                            <hr>
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    @else
                                        <strong> N/A </strong>
                                    @endif
                                </td>
                                <td>
                                    @if($product->isTaxable())

                                        <button class="btn btn-xs btn-success">Taxable</button>
                                    @else
                                        <button class="btn btn-xs btn-danger">Non Taxable</button>
                                    @endif
                                </td>
                                <td>
                                    @can('Update Product Status')
                                        <label class="switch">
                                            <input class="toggleStatus" href="{{route('admin.vendor.products.toggle-status',$product->product_code)}}" data-productCode="{{$product->product_code}}" type="checkbox" {{($product->is_active) === 1 ?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    @endcan
                                </td>

                                <td>

                                @can('Show Product')
                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('admin.products.show', $product->product_code),'Details', 'eye','info')!!}
                                @endcan

                                <!-- Single button -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            More <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{route('admin.warehouse-products.warehouses-stock',$product->product_code)}}">Warehouse Stocks</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10">
                                    <p class="text-center"><b>No records found!</b></p>
                                </td>

                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="pagination" id="vendorProduct-pagination">
                        @if(isset($products))
                            {{$products->appends($_GET)->links()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function (){
        $('#success').hide();
        $('#failed').hide();
        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change Product status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: href,
                        data:{
                            _token: "{{ csrf_token() }}"
                        },
                    }).done(function(data) {
                        console.log(data.is_active);
                        $("#success").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success").slideUp(500);
                        });
                    }).fail(function(error){
                        console.log(error);
                        $("#failed").fadeTo(2000, 500).slideUp(500, function(){
                            $("#failed").slideUp(500);
                        });
                        if (status === 0) {
                            $('.toggleStatus').prop('checked', true);
                        } else if (status === 1) {
                            $('.toggleStatus').prop('checked', false);
                        }
                    });
                }else if (result.isDenied) {
                    if (status === 0) {
                        $(this).prop('checked', true);
                    } else if (status === 1) {
                        $(this).prop('checked', false);
                    }
                }
            })
        })
    });

</script>



