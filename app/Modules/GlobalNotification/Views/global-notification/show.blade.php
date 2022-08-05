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
                            <strong>Notification Code:{{ $notificationDetail['global_notification_code'] }}</strong><br><br>
                            <strong>Link: <em><a href ="{{$notificationDetail->link}}" >{{$notificationDetail->link}}</a></em></strong><br><br>
                            <strong>Start Date: {{date('d-M-Y',strtotime($notificationDetail->start_date))}}</strong><br><br>
                            <strong>Expire Date: {{date('d-M-Y',strtotime($notificationDetail->end_date))}}</strong><br><br>
                            <strong>Created By: {{  ucfirst($notificationDetail->createdBy->name)}} </strong><br><br>
                            <strong>Created For: {{  ucfirst($notificationDetail->created_for) }} </strong><br><br>
                            <strong>Is Active:
                            @if($notificationDetail->is_active==1)
                                    <span class="label label-success">Yes</span>
                                @else
                                    <span class="label label-danger">No</span>
                                @endif
                            </strong><br><br>
                            <strong>Message:</strong><br>
                            {{ucfirst(strip_tags($notificationDetail->message))}}<br><br>

                            <strong>File:</strong><br>
                            <img src="{{asset('uploads/globalNotification/files/'.$notificationDetail['file'])}}"
                                 alt="" width="550"
                                 height="500">


                        </div>



                   </div>




                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include('Store::admin.store-payment.misc.misc-scripts')
@endpush

