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
                            <form action="{{ route('admin.notification.index') }}" method="get">

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="start_date">Start  Date </label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                               value="{{($filterParameters['start_date'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date </label>
                                        <input type="date" class="form-control" name="end_date" id="end_date"
                                               value="{{($filterParameters['end_date'])}}">
                                    </div>
                                </div>


                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="created_for">Created For</label>
                                        <select name="created_for" class="form-control" id="created_for">
                                            <option value="">Select All </option>
                                            <option value="all" {{($filterParameters['created_for'] =="all")?'selected':''}} >Global Users</option>
                                            <option value="store" {{($filterParameters['created_for'] =="store")?'selected':''}}>Store</option>
                                            <option value="vendor" {{($filterParameters['created_for'] =="vendor")?'selected':''}}>Vendor</option>
                                            <option value="warehouse" {{($filterParameters['created_for'] =="warehouse")?'selected':''}}>WareHouse</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <select name="is_active" class="form-control" id="is_active">
                                           <option value="" {{is_null($filterParameters['is_active'])?'selected':''}}>All</option>
                                            <option value="1" {{(isset($filterParameters['is_active']) && $filterParameters['is_active'] == 1)?'selected':''}}>Active</option>
                                            <option value="0" {{ (isset($filterParameters['is_active']) && $filterParameters['is_active'] == 0)?'selected':''}}>Inactive</option>
                                        </select>
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
                                List of Notification
                            </h3>


                            @can('Create Global Notification')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.notification.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Notification
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Text</th>
                                    <th>Created_for</th>
                                    <th>Start_date</th>
                                    <th>End_date</th>
                                    <th>Is_active</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse($allNotification as $key =>$notification)

                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{substr(ucfirst((strip_tags($notification->message))),0,20)}}</td>
                                        <td>{{ucfirst($notification->created_for)}}</td>
                                        <td> {{ date('d-M-Y',strtotime($notification['start_date']))}}</td>
                                        <td> {{ date('d-M-Y',strtotime($notification['end_date']))}}</td>
                                        <td>
                                            @can('Update Global Notification Status')
                                            @if($notification->is_active==1)
                                                <a href="{{route('admin.global-notification.toggle-status',$notification->global_notification_code)}}" class=" changeStatus "><span class="label label-success ">Yes</span></a>
                                            @else
                                                <a href="{{route('admin.global-notification.toggle-status',$notification->global_notification_code)}}" class=" changeStatus"><span class="label label-danger ">No</span></a>
                                             @endif
                                            @endcan
                                        </td>
                                        <td>
                                            @can('Show Global Notification')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.notification.show',$notification->global_notification_code ),'Detail Notification', 'eye','primary')!!}
                                            @endcan

                                            @can('Update Global Notification')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.notification.edit',$notification->global_notification_code ),'Edit Notification', 'pencil','warning')!!}
                                            @endcan

                                            @can('Delete Global Notification')
                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.notification.destroy',$notification->global_notification_code ),$notification,'GlobalNotification','Notification' )!!}
                                            @endcan

                                        </td>

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
                            {{$allNotification->appends($_GET)->links()}}

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

        <script>
            $('.changeStatus').click(function (e){
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure you want to change notification status ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10em',
                    width:'500px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    } else if (result.isDenied) {
                        Swal.fire('changes not saved', '', 'info')
                    }
                })
            })

        </script>
    @endpush
