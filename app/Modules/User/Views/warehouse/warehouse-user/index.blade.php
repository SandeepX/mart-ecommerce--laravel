@extends('AdminWarehouse::layout.common.masterlayout')
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


                                @can('Create WH User')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route("{$base_route}create") }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{$title}}
                                    </a>
                                </div>
                                @endcan
                            </div>


                            <div class="box-body">
                                <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Type</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Active Status</th>
                                        {{--<th>Remarks</th>--}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $i => $user)
                                       <tr>
                                           <td>{{++$i}}</td>
                                           <td>{{$user->userType->user_type_name}}</td>
                                           <td>{{$user->name}}</td>
                                           <td>{{$user->login_email}}</td>
                                           <td>{{$user->login_phone}}</td>
                                           <td>
                                               @if($user->is_active)
                                                   @php
                                                       $activeStatus = 'Deactivate';
                                                   @endphp
                                                   <span class="label label-success">On</span>
                                               @else
                                                   @php
                                                       $activeStatus = 'Activate';
                                                   @endphp
                                                   <span class="label label-danger">Off</span>
                                               @endif

                                           </td>
                                           {{--<td>{{$user->remarks}}</td>--}}
                                           <td>
                                              @if($user->userType->slug === 'warehouse-user')
                                              @can('Update WH User')
                                               {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'edit', $user->user_code),"Edit {$title}", 'pencil','primary')!!}
                                               @endcan
                                                   @can('Change WH User Status')
                                                   {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route('warehouse.warehouse-users.toggle-status',['userCode'=>$user->user_code]),'Change Status', 'pencil','primary')!!}
                                                   @endcan
                                                   @can('Delete WH User')
                                                   {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route($base_route.'destroy',$user->user_code),$user,"Delete {$title}",$user->name)!!}
                                                   @endcan
                                               @endif

                                           </td>
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
