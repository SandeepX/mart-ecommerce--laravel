<!-- Horizontal Form -->
<div class="box box-info">
    <!-- form start -->

    <div class="box-body">

        <!-- product search form -->

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" id="product-filter-form">
                   {{-- {{csrf_field()}}--}}
                    <div class="row">
                        <div class="col-md-12">

                            <label for="vendor_code">Vendor</label>
                            <select required class="select2 form-control" id="vendor_code" name="vendor_code"
                                    style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="" selected disabled>--Select Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{$vendor->vendor_code}}">{{$vendor->vendor_name}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-6">

                            <label for="category_code">Category</label>
                            <select class="select2 form-control" id="category_code" name="category_codes[]" multiple
                                    style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="" disabled>All categories</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->category_code}}">{{$category->category_name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6">

                            <label for="brand_code">Brand</label>
                            <select class="select2 form-control" id="brand_code" name="brand_code" style="width: 100%;"
                                    tabindex="-1" aria-hidden="true">
                                <option value="" selected >All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{$brand->brand_code}}">{{$brand->brand_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <div class="input-group input-group-md">
                                <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Product name">
                                <span class="input-group-btn">
                                        <button type="submit" class="btn btn-info btn-flat show_all">Search</button>
                                    </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>
        <!-- /.product search form -->

        <!-- product table -->
        <div class="row">
            <div class="col-md-12">
                <div id="product_list_tbl">
                    @include('AlpasalWarehouse::warehouse.warehouse-purchase-orders.form_partials.products-tbl')
                </div>

                <!-- </div> -->
                <!-- </div> -->
            </div>
        </div>
        <!-- /.product table -->

    </div>
    <!-- /.box-body -->

</div>
<!-- /.box -->