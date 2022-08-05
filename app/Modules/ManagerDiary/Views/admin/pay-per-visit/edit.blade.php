@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Edit {$title}",
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

                            <h3 class="box-title">Edit  {{$title}}   </h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.manager-pay-per-visits.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="updateManagerPayPerVisit" action="{{route($base_route.'.update',$managerPayPerVisit->manager_pay_per_visit_code)}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}
                                @method('PUT')
                                <div class="box-body">
                                    <div class="form-group">
                                         <div class="col-sm-8">
                                           <h4 style="text-align: center">{{$managerPayPerVisit->manager->manager_name}} ({{$managerPayPerVisit->manager_code}})</h4>
                                         </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Amount</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" step="0.01" value="{{$managerPayPerVisit->amount}}" placeholder="Enter the Pay Per Visit Amount" name="amount" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary addNewBankDetail">Edit</button>
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

    {{--    <script>--}}
    {{--            alert('its changed');--}}
    {{--    </script>--}}



@endsection


@push('scripts')
    <script>
        $('#updateManagerPayPerVisit').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to edit Manager Pay Per Visit  ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });
    </script>
@endpush
