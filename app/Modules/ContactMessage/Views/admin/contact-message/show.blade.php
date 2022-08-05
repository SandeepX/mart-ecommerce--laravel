@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> "Show the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box">
                        <div class="box-header with-border">

                            <h3 class="box-title">Details of {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <h4>Name : {{$contactMessage->name}}</h4>
                                        <h4>Email : {{$contactMessage->email}}</h4>
                                        <h4>Phone no. : {{$contactMessage->phone}}</h4>
                                        <div class="row">
                                            <div class="col-3">
                                                <h4>Message</h4>
                                            </div>

                                            <div class="col-9">
                                               <p>
                                                   {{$contactMessage->message}}
                                               </p>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
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

