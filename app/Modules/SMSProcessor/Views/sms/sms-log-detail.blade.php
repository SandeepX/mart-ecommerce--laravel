@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">



                        <div class="panel-body">
                            <form action="{{route('admin.sms.index')}}" method="get">

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="smsCode">SMS Code </label>
                                        <input type="text" class="form-control" name="smsCode" id="smsCode"
                                               value="{{$filterParameters['sms_code']}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="purpose">Purpose </label>
                                        <input type="text" class="form-control" name="purpose" id="purpose"
                                               value="{{$filterParameters['purpose']}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="created_from">Created From </label>
                                        <input type="date" class="form-control" name="created_from" id="created_from"
                                               value="{{$filterParameters['created_from']}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="created_to">Created To  </label>
                                        <input type="date" class="form-control" name="created_to" id="created_to"
                                               value="{{$filterParameters['created_to']}}">
                                    </div>
                                </div>


                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>

                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of SMS
                            </h3>
                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SmsCode</th>
                                    <th>Purpose</th>
                                    <th>Created At</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse($smsDetail as $key =>$detail)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$detail->sms_master_code }}</td>
                                        <td>{{convertToWords($detail->purpose)}}</td>

                                        <td>{{ getReadableDate(getNepTimeZoneDateTime($detail->created_at)) }}</td>
                                        <td>
                                            @can('Show sms detail')

                                                <button type="button" class="btn btn-info btn-xs viewSmsDetail" data-request-body="{{$detail->request_body}}" data-store-code="{{$detail->balanceMasterDetail ? $detail->balanceMasterDetail->store_code  : '' }}" data-response-body="{{($detail->response_body)}}" data-toggle="modal" data-target="#myModal">view detail</button>
{{--                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.sms.show',$detail->sms_master_code ),'Detail Sms Detail', 'eye','primary')!!}--}}
                                            @endcan

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
                            {{$smsDetail->appends($_GET)->links()}}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><b class="message-response"></b> Store Code: <b class="store-code"></b></h4>
                    </div>
                    <div class="modal-body">
                      <div class="col-sm-6">
                          <p><h3 class="response-heading">Request Body</h3></p>
                          <b>Mobile number:</b>  <p class="request-bodyto"></p>
                          <b>Message:</b> <p class="request-bodymessage"></p>
                      </div>


                        <div class="col-sm-6">
                            <p><h3 class="response-heading">Response Body</h3></p>
                            <b>count:</b>  <p class="response-body-count"></p>
                            <b>Response Code:</b>  <p class="response-body-response-code"></p>
                            <b>Response:</b>  <p class="response-body-response"></p>
                            <b>Message Id:</b>  <p class="response-body-message-id"></p>
                            <b>Credit Consumed:</b>  <p class="response-body-credit-consumed"></p>
                            <b>credit Available:</b>  <p class="response-body-credit-Available"></p>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default closeDetail" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- /.content -->
    </div>



@endsection


@push('scripts')
    <script>
        $(document).ready(function(){

            $('.viewSmsDetail').on('click',function() {
                var requestBodyData = JSON.parse($(this).attr('data-request-body'));
                var store_code = $(this).attr('data-store-code');

                $('.request-bodyto').text(requestBodyData.to);
                $('.request-bodymessage').text(requestBodyData.message);

                var responseBodyData = JSON.parse($(this).attr('data-response-body'));
                if(responseBodyData.count == null){

                    $('.response-body-count').text('');
                    $('.response-body-response-code').text(responseBodyData.response_code);
                    $('.response-body-response').text(responseBodyData.response);
                    $('.response-body-message-id').text('');
                    $('.response-body-credit-consumed').text('');
                    $('.response-body-credit-Available').text('');
                    $('.message-response').text("Detail of Sms:(Failed case)");
                    $('.store-code').text(store_code);

                    $('.response-heading').css('color','red');

                }else{

                    $('.response-body-count').text(responseBodyData.count);
                    $('.response-body-response-code').text(responseBodyData.response_code);
                    $('.response-body-response').text(responseBodyData.response);
                    $('.response-body-message-id').text(responseBodyData.message_id);
                    $('.response-body-credit-consumed').text(responseBodyData.credit_consumed);
                    $('.response-body-credit-Available').text(responseBodyData.credit_available);
                    $('.message-response').text("Detail of Sms:(Success case)");
                    $('.store-code').text(store_code);
                    $('.response-heading').css('color','green');
                }


            });

            // $('.closeDetail').on('click',function(){
            //     location.reload();
            // });

        });

    </script>


@endpush

