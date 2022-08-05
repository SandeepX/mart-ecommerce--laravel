
{{--<section class="content">--}}
{{--    <div class="row">--}}
{{--        <div class="col-xs-12">--}}
{{--        </div>--}}
{{--        <div class="col-xs-12">--}}
{{--            <div class="panel panel-default">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-5">--}}
{{--                        <h3 style="margin-left:10px; font-weight: bold;">List of Pre-Orders</h3>--}}
{{--                        --}}{{--                        <p style="margin-left: 10px;">Updated information: <a href="#">2 min ago</a></p>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3">--}}
{{--                        <h3 style="font-weight: bold;">{{$preOrdersListing->total()}}</h3>--}}
{{--                        <p>Total Pre-Orders</p>--}}
{{--                    </div>--}}

{{--                    <div class="col-md-4">--}}
{{--                        <a style="margin-top: 30px !important;" class="btn btn-danger" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseExample">--}}
{{--                            <i class="fa  fa-filter"></i>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">--}}
{{--                <div class="panel-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <form id="pre_order_filter_form" action="{{route('support-admin.store-preorder',$storeCode)}}" method="GET">--}}
{{--                                <div class="col-xs-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="pre_order_name">Pre-Order Name</label>--}}
{{--                                        <input type="text" class="form-control" name="pre_order_name" id="pre_order_name"--}}
{{--                                               value="{{$filterParameters['pre_order_name']}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-xs-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="payment_status">Payment Status</label>--}}
{{--                                        <select class="form-control " id="payment_status" name="payment_status" >--}}
{{--                                            <option value="" {{ !isset($filterParameters['payment_status']) ? 'selected':''}}>All</option>--}}
{{--                                            <option value="1" {{ isset($filterParameters['payment_status']) && $filterParameters['payment_status'] == 1  ? 'selected':''}}>Paid</option>--}}
{{--                                            <option value="0" {{ isset($filterParameters['payment_status']) && $filterParameters['payment_status'] == 0  ? 'selected':''}}>Unpaid</option>--}}
{{--                                        </select>--}}

{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-xs-4">--}}
{{--                                    <label for="status">Status</label>--}}
{{--                                    <select class="form-control " id="status" name="status" >--}}
{{--                                        <option value="" {{!isset($filterParameters['status']) ? 'selected':''}} >All</option>--}}
{{--                                        @foreach($preOrderStatuses as $preOrderStatus)--}}
{{--                                            <option value="{{$preOrderStatus}}"--}}
{{--                                                {{(isset($filterParameters['status']) && $preOrderStatus == $filterParameters['status'])? 'selected' :''}}>--}}
{{--                                                {{ucwords($preOrderStatus)}}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}

{{--                                <br><br>--}}
{{--                                <div class="col-xs-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <button id="pre-order-filter-btn" type="button" class="btn btn-block btn-primary form-control">Filter</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}

{{--        <!-- preorder list table -->--}}
{{--        <div class="col-xs-12">--}}
{{--            <div class="panel panel-primary">--}}
{{--                <div class="panel-heading">--}}
{{--                    <h3 class="panel-title">--}}
{{--                        List of Pre-Orders--}}
{{--                    </h3>--}}

{{--                </div>--}}

{{--                <div class="box-body">--}}

{{--                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>S.N</th>--}}
{{--                            <th>PRE ORDER</th>--}}
{{--                            <th>STATUS</th>--}}
{{--                            <th>PAYMENT STATUS</th>--}}
{{--                            <th>AMOUNT</th>--}}
{{--                            <th>ORDER CREATED</th>--}}
{{--                            <th>ACTION</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        {{dd($preOrdersListing)}}--}}

{{--                        @forelse($preOrdersListing as $i => $preOrder)--}}

{{--                            <tr>--}}

{{--                                <td>{{++$i}}</td>--}}
{{--                                <td>--}}
{{--                                    {{$preOrder->pre_order_name}}({{$preOrder->warehouse_preorder_listing_code}})--}}
{{--                                    <br>--}}
{{--                                    Start Time : {{$preOrder->start_time}} <br>--}}
{{--                                    End Time : {{$preOrder->end_time}}--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                <span class="label label-{{returnLabelColor($preOrder->status)}}">--}}
{{--                                     {{$preOrder->status}}--}}
{{--                                </span>--}}

{{--                                </td>--}}
{{--                                <td>--}}
{{--                                  <span class="label label-{{returnLabelColor($preOrder->payment_status)}}">--}}
{{--                                     {{$preOrder->payment_status == 1 ? 'Paid': 'Unpaid'}}--}}
{{--                                </span>--}}

{{--                                </td>--}}
{{--                                <td>{{$preOrder->total_price}}</td>--}}
{{--                                <td>{{$preOrder->created_at}}</td>--}}

{{--                                <td>--}}
{{--                                    <a>--}}
{{--                                        <button data-toggle="modal" value="{{$preOrder->store_preorder_code}}"--}}
{{--                                                data-url="{{route('support-admin.store-preorder-detail',['storePreOrderCode'=> $preOrder->store_preorder_code])}}"--}}
{{--                                                data-target="#modal-target1"--}}
{{--                                                id="preorder_view_btn"--}}
{{--                                                data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">--}}
{{--                                            <span class="fa fa-eye"></span>--}}
{{--                                            Details--}}
{{--                                        </button>--}}
{{--                                    </a>--}}

{{--                                    --}}{{--                                    @can('Show Store PreOrder')--}}
{{--                                    <div class="modal fade" id="modal-target1" >--}}
{{--                                        <div class="modal-dialog" style="width: 80% !important; height: 90vh; overflow: scroll;">--}}
{{--                                            <div class="preorder-detail-modal-content" style="background-color: white" >--}}

{{--                                            </div>--}}
{{--                                            <!-- /.modal-content -->--}}
{{--                                        </div>--}}
{{--                                        <!-- /.modal-dialog -->--}}
{{--                                    </div>--}}
{{--                                    --}}{{--                                    @endcan--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                        @empty--}}
{{--                            <tr>--}}
{{--                                <td colspan="10">--}}
{{--                                    <p class="text-center"><b>No records found!</b></p>--}}
{{--                                </td>--}}

{{--                            </tr>--}}
{{--                        @endforelse--}}
{{--                        </tbody>--}}
{{--                    </table>--}}

{{--                    <div class="pagination" id="preorder-pagination">--}}
{{--                        @if(isset($preOrdersListing))--}}
{{--                            {{$preOrdersListing->appends($_GET)->links()}}--}}
{{--                        @endif--}}
{{--                    </div>--}}


{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- end of preorder list table -->--}}
{{--    </div>--}}
{{--    <!-- /.row -->--}}
{{--</section>--}}


