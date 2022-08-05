@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
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
                                List of {{formatWords($title,true)}}
                            </h3>


                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Type</th>
                                    <th>Name</th>
                                    <th>Vendor</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Active Status</th>
                                    {{--<th>Remarks</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $i => $user)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$user->userType->user_type_name}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->vendor->vendor_name}}</td>
                                        <td>{{$user->login_email}}</td>
                                        <td>{{$user->login_phone}}</td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="label label-success">On</span>
                                            @else
                                                <span class="label label-danger">Off</span>
                                            @endif

                                        </td>
                                        {{--<td>{{$user->remarks}}</td>--}}
                                       {{-- <td>
                                            @can('Update Vendor Admin')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'.edit', $user->user_code),"Edit {$title}", 'pencil','primary')!!}
                                            @endcan



                                           {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route($base_route.'.destroy',$user->user_code),$user,"Delete {$title}",$user->name)!!}

                                        </td>--}}
                                    </tr>
                                @endforeach
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