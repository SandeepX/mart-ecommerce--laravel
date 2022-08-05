@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Compose Message</h3>
                            @can('View Global Notification List')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.notification.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="sendMessage" action="{{route('admin.message.send-message')}}"  method="post">
                             @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="phone Number">Phone Number</label>
                                        <input type="number" class="form-control" id="phone" name="phone" required>

                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <input type="text" class="form-control" id="message" name="message" required>
                                    </div>


                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">send Message</button>
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

@push('scripts')

    <script>

    </script>
@endpush

