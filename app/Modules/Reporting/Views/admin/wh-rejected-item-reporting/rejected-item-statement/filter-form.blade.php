
<div class="col-xs-12">
    <div class="panel-group">
        <div class="panel panel-success">
            <div class="panel-heading">
                <strong >
                    FILTER ITEM REJECTED RECORDS
                </strong>

                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm">
                        <strong>Filter</strong> <i class="fa fa-filter"></i>
                    </button>
                </div>
            </div>
            <div class="panel-body" >
                <div class="panel panel-default">
                    <div class="{{(isset($filterParameters) && !empty($filterParameters))?'':'collapse'}}" id="filter">
                        <div class="panel-body" >
                            <form action="{{route('admin.wh-rejected-item-report-statement.index')}}" method="get">

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="warehouse">Warehouse</label>
                                        <select id="warehouse" name="warehouse_code" class="form-control select2">
                                            <option value="" {{($filterParameters['warehouse_code']) ? 'selected' : ''}}>All</option>
                                            @foreach($warehouse as $key => $value)
                                            <option value="{{$value->warehouse_code}}" {{($filterParameters['warehouse_code'] ==$value->warehouse_code ) ? 'selected' : ''}}>{{$value->warehouse_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="order_type">Order Types</label>
                                        <select class="form-control select2" name="order_type" id="order_type">
                                            <option value="">All</option>
                                            @foreach($orderTypes as $orderType)
                                                <option value="{{$orderType}}" {{($orderType == $filterParameters['order_type'] ? 'selected' : '')}}>{{ucwords(str_replace('_',' ',$orderType))}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

{{--                                <div class="col-xs-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="vendor_name"> Vendor Name </label> <button class="btn btn-danger btn-xs" id="reset-vendor_name"> Reset</button>--}}
{{--                                        <select id="vendor_code" name="vendor_code" class="form-control select2" >--}}
{{--                                            <option value="" {{($filterParameters['vendor_code']) ? '' : 'selected'}}>All</option>--}}
{{--                                            @foreach($vendors as $key => $value)--}}
{{--                                            <option value="{{$value->vendor_code}}" {{($value->vendor_code==$filterParameters['vendor_code']) ? 'selected' : ''}}>{{$value->vendor_name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}



                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="product">Product Name <button class="btn btn-danger btn-xs" id="reset-product"> Reset</button></label>
                                        <input type="text"  class="form-control" name="product_name" id="product_name"
                                               value="{{($filterParameters['product_name'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="product_variant_name">Product Variant Name <button class="btn btn-danger btn-xs" id="reset-product_variant_name"> Reset</button></label>
                                        <input type="text" class="form-control" name="product_variant_name" id="product_variant_name"
                                               value="{{($filterParameters['product_variant_name'])}}">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="from_date" class="control-label">Order Date From</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date" value="{{$filterParameters['from_date']}}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="to_date">Order Date To</label>
                                        <input type="date" class="form-control" name="to_date" id="to_date" value="{{$filterParameters['to_date']}}">
                                    </div>
                                </div>


                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="">Records Per Page</label>
                                        <input type="number" min="25" step="25" class="form-control" name="per_page" id="per_page"
                                               value="{{($filterParameters['perPage'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group" style="padding-top: 25px;">
                                        <button  type="submit" id="submit" class="btn btn-block btn-info form-control">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
