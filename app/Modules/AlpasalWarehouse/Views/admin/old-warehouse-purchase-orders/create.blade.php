@extends('Admin::layout.common.masterlayout')

@section('content')
<div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "Create {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index')
    ])


    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">

                <!-- general form elements -->
                <div class="box box-primary">
                    <!-- form start -->
                    <form class="form-horizontal" id="pos-form">
                        <div class="box-header with-border" style="padding-bottom: 0px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <h3 class="box-title text-primary"><i class="fa fa-shopping-cart text-aqua"></i> Purchase Order</h3>
                                    </div>


                                </div>
                            </div>




                        </div>
                        <!-- /.box-header -->

                        <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Warehouse</label>
                                <select class="select2 form-control" id="warehouse" name="category_id">
                                    <option selected value=""> -- Select Warehouse --</option>
                                    @foreach($warehouses as $warehouse)
                                      <option value="{{$warehouse->warehouse_code}}">{{$warehouse->warehouse_name}}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                           
                        </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-12" style="overflow-y: auto; height: 350px;">
                                            <table class="table table-condensed table-bordered table-striped table-responsive items_table" style="">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th style="width:30%">Product</th>
                                                      
                                                        <th style=" width:7%">Qty</th>
                                                        <th style="width:15%">Price</th>
                                                        <th style="width:15%">Subtotal</th>
                                                        <th style="width:5%"><i class="fa fa-close"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pos-form-tbody" style="font-size: 16px;font-weight: bold;overflow: scroll;">
                                                  
                                                    <tr>
                                                      <td >
                                                      Gyan Suji
                                                      <br>
                                                      <small>variant-1</small>
                                                      </td>
                                                     
                                                      <td>
                                                      <input style="width:75px" type="number" value="1">
                                                      </td>
                                                      <td>
                                                      4500
                                                      </td>
                                                      <td>
                                                       4500
                                                      </td>
                                                      <td >
                                                       <i class="fa fa-trash"></i>
                                                      </td>
                                                    </tr>

                                                    <tr>
                                                      <td >
                                                      Gyan Suji
                                                      <br>
                                                      <small>variant-1</small>
                                                      </td>
                                                      
                                                      <td>
                                                      <input style="width:75px" type="number" value="1">
                                                      </td>
                                                      <td>
                                                      4500
                                                      </td>
                                                      <td>
                                                       4500
                                                      </td>
                                                      <td >
                                                       <i class="fa fa-trash"></i>
                                                      </td>
                                                    </tr>
                                                                                                  
                                                </tbody>
                                               
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer bg-gray">
                        
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <div class="col-sm-6">
                                        <button type="button" id="hold_invoice" name="" class="btn bg-maroon btn-block btn-flat btn-lg" title="Hold Purchase Order (Not Submitted to Vendor : Can Edit The Purchase Order Later On )">
                                            <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                                            Save as Draft
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" id="" name="" class="btn btn-primary btn-block btn-flat btn-lg"
                                         title="Directly Submit the Purchase Order to Vendor (Cannot Edit The Purchase Order After Direct Submit)">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            Send to Vendor 
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->

                    <div class="box-body">


                    <div class="row">
                            <div class="col-md-12">
                                <label>Vendor</label>
                                <select class="select2 form-control id="category_id" name="category_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="">--Select Vendor --</option>
                                    <option value="6">Jeans</option>
                                    <option value="10">Casual Shirts</option>
                                    <option value="11">Formal Shirts</option>
                                    <option value="12">T-Shirts</option>
                                    <option value="13">Jackets</option>
                                    <option value="14">Men Wears</option>
                                    <option value="15">Books</option>
                                    <option value="16">Computers</option>
                                    <option value="17">Shoes</option>
                                    <option value="18">Health Care</option>
                                    <option value="19">Watches</option>
                                    <option value="20">Mobiles</option>
                                    <option value="21">Accessories</option>
                                </select>
                                
                            </div>
                           
                        </div>

                        <div style="margin-top:10px" class="row">
                            <div class="col-md-6">
                                <label>Category</label>
                                <select class="select2 form-control id="category_id" name="category_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="">All Categories</option>
                                    <option value="6">Jeans</option>
                                    <option value="10">Casual Shirts</option>
                                    <option value="11">Formal Shirts</option>
                                    <option value="12">T-Shirts</option>
                                    <option value="13">Jackets</option>
                                    <option value="14">Men Wears</option>
                                    <option value="15">Books</option>
                                    <option value="16">Computers</option>
                                    <option value="17">Shoes</option>
                                    <option value="18">Health Care</option>
                                    <option value="19">Watches</option>
                                    <option value="20">Mobiles</option>
                                    <option value="21">Accessories</option>
                                </select>
                                
                            </div>
                            <div class="col-md-6">
                            <label>Brand</label>
                                <select class="select2 form-control id="category_id" name="category_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="">All Brands</option>
                                    <option value="6">Jeans</option>
                                    <option value="10">Casual Shirts</option>
                                    <option value="11">Formal Shirts</option>
                                    <option value="12">T-Shirts</option>
                                    <option value="13">Jackets</option>
                                    <option value="14">Men Wears</option>
                                    <option value="15">Books</option>
                                    <option value="16">Computers</option>
                                    <option value="17">Shoes</option>
                                    <option value="18">Health Care</option>
                                    <option value="19">Watches</option>
                                    <option value="20">Mobiles</option>
                                    <option value="21">Accessories</option>
                                </select>
                                
                            </div>
                        </div>

                       

                        <div style="margin-top:20px" class="row">
                            <div class="col-md-12">
                            <div class="input-group input-group-md">
                                    <input type="text" id="search_it" class="form-control" placeholder="Filter Items" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat show_all">Search</button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- <div class="form-group"> -->
                                <!--  <div class="col-sm-12"> -->
                                <!-- <style type="text/css">
                        
                      </style> -->

                                <section class="content" style="height: 405px;">
                                    <div class="row search_div" style="overflow-y: scroll;min-height: 100px;height: 350px;">
                                        
                                    <table class="table table-bordered table-striped">
    <thead>
      <tr>
      <th style="width:5%">Image</th>
        <th style="width:90%">Product</th>
        <th style="width=5%">Action</th>
        
     
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><img src="http://192.168.10.82:8000/uploads/vendors/logo/ei91bmi4wh_1603088088.png" alt="Vendor Logo" width="50" height="50"></td>
        <td>
         <p style="margin-top:10px">
         Gyan Suji
         </p>
        </td>
        <td> 
        <button style="margin-top:10px" title="Add to Purchase Order List" class="btn btn-sm btn-success">
        <i class="fa fa-shopping-cart"></i>
        
        </button>
        </td>

      </tr>

      <tr>
        <td><img src="http://192.168.10.82:8000/uploads/vendors/logo/ei91bmi4wh_1603088088.png" alt="Vendor Logo" width="50" height="50"></td>
        <td>
         <p style="margin-top:10px">
         Capsico Red Pepper Sauce 
         </p>
        </td>
        <td> 
        <button style="margin-top:10px" title="Add to Purchase Order List" class="btn btn-sm btn-success">
        <i class="fa fa-shopping-cart"></i>
        
        </button>
        </td>

      </tr>

      <tr>
        <td><img src="http://192.168.10.82:8000/uploads/vendors/logo/ei91bmi4wh_1603088088.png" alt="Vendor Logo" width="50" height="50"></td>
        <td>
         <p style="margin-top:10px">
         Natural Shine Henna
         </p>
        </td>
        <td> 
        <button style="margin-top:10px" title="Add to Purchase Order List" class="btn btn-sm btn-success">
        <i class="fa fa-shopping-cart"></i>
        
        </button>
        </td>

      </tr>
     
    </tbody>
  </table>
                                    </div>
                                    <h3 class="text-danger text-center error_div" style="display: none;">Sorry! No Records Found</h3>
                                </section>


                                <!-- </div> -->
                                <!-- </div> -->
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->



                </div>
                <!-- /.box -->

                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>



</div>

</section>

</div>



@endsection
