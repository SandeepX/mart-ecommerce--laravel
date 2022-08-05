@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])



    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            {{-- <form action="{{route('warehouse.warehouse-purchase-orders.index')}}" method="get">
                                <div class="col-xs-3">
                                    <label for="vendor">Vendor</label>
                                    <select id="vendor" name="vendor" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($vendors as $vendor)
                                            <option value="{{$vendor->vendor_code}}"
                                                    {{$vendor->vendor_code == $filterParameters['vendor_code'] ?'selected' :''}}>
                                                {{ucwords($vendor->vendor_name)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-xs-3">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($statuses as $status)
                                            <option value="{{$status}}"
                                                    {{$status == $filterParameters['status'] ?'selected' :''}}>
                                                {{ucwords($status)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-xs-3">
                                    <label for="order_date_from">Order Date From</label>
                                    <input type="date" class="form-control" name="order_date_from" id="order_date_from" value="{{$filterParameters['order_date_from']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="order_date_to">Order Date To</label>
                                    <input type="date" class="form-control" name="order_date_to" id="order_date_to" value="{{$filterParameters['order_date_to']}}">
                                </div>


                                <br><br>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                    </div>
                                </div>
                            </form> --}}
                        </div>
                    </div>
                </div>
                @can('View List Of WH Purchase Orders')
                    <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of Products
                                </h3>



                                {{-- <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('warehouse.warehouse-purchase-orders.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Purchase Order
                                    </a>
                                </div> --}}

                            </div>


                            <div class="box-body">

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Added On</th>
                                            <th>Active Status</th>
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
                                            <td>
                                                @if($product->is_active)
                                                @php
                                                $activeStatus = 'Deactivate';
                                                @endphp
                                                <span class="label label-success">On</span>
                                                @else
                                                @php
                                                $activeStatus = 'Activate';
                                                @endphp
                                                <span class="label label-danger">Off</span>
                                                @endif

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

                                {{-- {{$products->appends($_GET)->links()}} --}}
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
                <!-- /.row -->
        </section>
    <!-- /.content -->

    </div>
@endsection
