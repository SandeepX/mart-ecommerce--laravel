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
                            <form class="form-horizontal" action="https://uat.connectips.com/connectipswebgw/loginpage" enctype="multipart/form-data" method="post">

                                    <input type="hidden" name="MERCHANTID"  value="428"/>
                                    <input type="hidden" name="APPID" value="MER-428-APP-1"/>
                                    <input type="hidden" name="APPNAME" value="AllPasal"/>
                                    <input type="hidden" name="TXNID" value="{{$payment->txn_id}}"/>
                                    <input type="hidden" name="TXNDATE" value="{{$payment->txn_date}}"/>
                                    <input type="hidden" name="TXNCRNCY" value="{{$payment->txn_currency}}"/>
                                    <input type="hidden" name="TXNAMT" value="{{$payment->txn_amount}}"/>
                                    <input type="hidden" name="REFERENCEID" value="{{$payment->reference_id}}"/>
                                    <input type="hidden" name="REMARKS" value="{{$payment->remarks}}"/>
                                    <input type="hidden" name="PARTICULARS" value="{{$payment->particulars}}"/>
                                    <input type="hidden" name="TOKEN" value="{{$token}}"/>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Confirm</button>
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

