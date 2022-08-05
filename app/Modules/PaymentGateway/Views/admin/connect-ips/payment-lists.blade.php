@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of Payments
                                </h3>
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.connect-ips.payment-form')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Payment
                                    </a>
                                </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Logo</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
