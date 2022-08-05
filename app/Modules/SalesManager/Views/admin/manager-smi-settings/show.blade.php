@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Show Detail {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <div class="box-body">
                            <strong>MSMI Setting Code:{{ $managerSMISetting['global_notification_code'] }}</strong><br><br>
                            <strong>Created Date: {{date('d-M-Y',strtotime($managerSMISetting->created_at))}}</strong><br><br>
                            <strong>Updated Date: {{date('d-M-Y',strtotime($managerSMISetting->updated_at))}}</strong><br><br>
                            <strong>Created By: {{  ucfirst($managerSMISetting->createdBy->name)}} </strong><br><br>
                            <strong>Updated By: {{  ucfirst($managerSMISetting->updatedBy->name) }} </strong><br><br>

                            <strong>Terms And Condition:</strong><br>
                            {{ucfirst(strip_tags($managerSMISetting->terms_and_condition))}}<br><br>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection


