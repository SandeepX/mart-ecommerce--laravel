@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @includeIf('Admin::layout.partials.breadcrumb',
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=>'Add A '. formatWords($title,false),
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div  id="showFlashMessage"></div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="text-left">
                                    <h3 class="panel-title">
                                        <b>New Lead Registration Form</b>
                                        &nbsp;
                                    </h3>
                                    <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                        <a href="{{ route($base_route . '.index') }}" style="border-radius: 0px; "
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-list"></i>
                                            List of {{ formatWords($title, true) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form id="lead_form" enctype="multipart/form-data" method="POST">
                                    {{-- @csrf --}}
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <div class="nav-tabs-custom">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="registration">
                                                @include(''.$module.'.admin.common.form')                                                
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>




                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection


@push('scripts')
@includeIf('Lead::admin.scripts.store-lead-script');
@endpush


