@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.flash_message')
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Show Product Details",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index')
        ])
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h5 class="page-header">
                        Product : {{($product->product_name)}}
                        <small class="pull-right">Created on: 2/10/2014</small>
                    </h5>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">


                <!-- /.col -->
                <div class="col-sm-12 ">
                    <b>Product # {{$product->product_code}}</b><br>

                    <b>Brand :</b> {{$product->brand->brand_name}}<br>
                    <b>Category :</b> {{$product->category->category_name}}<br>
                    <br>

                    <b>Verification Status
                        :</b> {{isset($product->verification) ? $product->verification->verification_status : 'not verified'}}
                    <br>
                    <b>Verification Date
                        :</b> {{isset($product->verification) ? $product->verification->verification_date : ''}}<br>

                </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="box box-success">
                                <div class="box-header with-border">

                                    <div>

                                        @can('Verify Product')
                                            <button data-toggle="modal"
                                                    style="border-radius: 0px; " class="btn btn-sm btn-info"
                                                    data-target="#createProductVerificationModal"
                                            >
                                                <i class="fa fa-list"></i>
                                                Verify Product
                                            </button>

                                            @include('Product::admin.product.product-verification.create',[
                                                'targetModalID'=>'createProductVerificationModal',
                                                'product'=> $product
                                            ])
                                        @endcan

                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title">Product Verification Logs</h3>
                            <table id="data-table" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Old Verification Status</th>
                                    <th>New Verification Status</th>
                                    <th>Old Verification Date</th>
                                    <th>New Verification Date</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($product->verification))
                                    @foreach($product->verification->verificationDetails as $i => $verificationDetail)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$verificationDetail->old_verification_status}}</td>
                                            <td>{{$verificationDetail->new_verification_status}}</td>
                                            <td>{{$verificationDetail->old_verification_date}}</td>
                                            <td>{{$verificationDetail->new_verification_date}}</td>
                                            <td>{{$verificationDetail->remarks}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
            </div>

            <br>
        </section>
        <!-- /.content -->
    </div>



@endsection