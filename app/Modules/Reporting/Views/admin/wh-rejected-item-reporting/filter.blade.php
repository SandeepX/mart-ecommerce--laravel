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
                            <form id="filter_form" action="#" method="GET">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <label for="warehouse">Warehouse</label>
                                            <select id="warehouse" name="warehouse_code" class="form-control select2">
                                                @foreach($warehouse as $key => $value)
                                                    <option value="{{$value->warehouse_code}}" {{($loop->index==0) ? 'selected' : ''}}>{{$value->warehouse_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <label for="vendor">Vendor <button class="btn btn-danger btn-xs" id="reset-vendor">Reset</button></label>
                                            <select id="vendor" name="vendor_code[]" multiple class="form-control select2">

                                            </select>
                                        </div>
                                        <div class="form-group">

                                        </div>
                                    </div>

                                    <div class=" col-lg-5 col-md-5">
                                        <div class="form-group">
                                            <label for="product">Product <button class="btn btn-danger btn-xs" id="reset-product">Reset</button></label>
                                            <select id="product" name="product_code" class="form-control select2">
                                                <option value="" disabled selected>Select Product</option>
                                            </select>
                                        </div>
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

                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="">Records Per Page</label>
                                        <input type="number" min="25" step="25" class="form-control" name="per_page" id="per_page"
                                               value="{{($filterParameters['perPage'])}}">
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary form-control" style="margin-top: 24px;">Filter</button>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group">
                                        <a href="{{route('admin.wh-rejected-item-reporting.index')}}"  class="btn btn-block btn-danger form-control" style="margin-top: 24px;">Clear</a>
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




