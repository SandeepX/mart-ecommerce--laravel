@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.payment-lists'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">Add A {{$title}}</h3>
                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="#" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="createBank" action="{{route('admin.connect-ips.payment-store')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Transaction Amount</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="{{ old('txn_amount') }}" placeholder="Enter the Transaction Amount" name="txn_amount" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Reference ID</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{old('reference_id')}}" placeholder="Enter the Referecnce Id" name="reference_id"  autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Remarks</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" placeholder="Enter Remarks" name="remarks"  autocomplete="off">{{old('remarks')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-2 control-label">Particulars</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{old('particulars')}}" placeholder="Enter the Particulars" name="particulars"  autocomplete="off">
                                        </div>
                                    </div>

                                </div>

                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>


@endsection

