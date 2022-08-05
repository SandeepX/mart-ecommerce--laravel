@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #ff667a ;
        }

        .warning-color {
            background-color:  #f5c571 ;
        }


    </style>
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
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.users.index')}}" method="get">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="user_name">User Type</label>
                                        <select name="user_type" class="form-control select2" >
                                            <option value="">All</option>
                                            @foreach($userTypes as $userType)
                                            <option value="{{$userType->user_type_code}}" {{ $filterParameters['user_type'] == $userType->user_type_code ? "selected" : '' }}>{{$userType->user_type_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="user_name">User Name</label>
                                        <input type="text" class="form-control" name="user_name" id="user_name" value="{{$filterParameters['user_name']}}">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email" value="{{$filterParameters['email']}}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of {{formatWords($title,true)}}
                                </h3>

                                @can('Create Admin')
                                    <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                        <a href="{{ route("{$base_route}.create") }}" style="border-radius: 0px; "
                                           class="btn btn-sm btn-info">
                                            <i class="fa fa-plus-circle"></i>
                                            Add New {{$title}}
                                        </a>
                                    </div>
                                @endcan
                            </div>


                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <span class='box-color danger-color'></span>  Banned
                                        {{--                                         <span class='box-color warning-color'></span> Suspened--}}
                                    </div>
                                    <div class="col-sm-2" style="margin-left: -50px !important">
                                        {{--                                         <span class='box-color danger-color'></span>  Banned--}}
                                        <span class='box-color warning-color'></span> Suspened
                                    </div>
                                </div>
                                <table class="table table-bordered " cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Type</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Active</th>
                                        {{--<th>Remarks</th>--}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($users as $i => $user)
                                       <tr class=" @if($user->isBanned())bg-danger @elseif($user->isSuspended())bg-warning @endif">
                                           <td>{{++$i}}</td>
                                           <td>{{$user->userType->user_type_name}}</td>
                                           <td>{{$user->name}}</td>
                                           <td>{{$user->user_code}}</td>
                                           <td>{{$user->login_email}}</td>
                                           <td>{{$user->login_phone}}</td>
                                           <td>
                                               @if($user->isActive())
                                                   <a href="{{route('admin.user-account-log.toggleActive',$user->user_code)}}" title="DeActivate User" class="user-deactivate"
                                                      data-user-code="{{$user->user_code}}"
                                                      data-user-type="{{$user->userType->user_type_name}}"
                                                      data-user-name="{{$user->name}}"
                                                   >
                                                       <span class="label label-danger"> DeActivate </span>
                                                   </a>
                                               @else
                                                   <a href="{{route('admin.user-account-log.toggleActive',$user->user_code)}}" title="Activate User" class="user-activate"
                                                      data-user-code="{{$user->user_code}}"
                                                      data-user-type="{{$user->userType->user_type_name}}"
                                                      data-user-name="{{$user->name}}"
                                                   >
                                                       <span class="label label-success">Activate</span>
                                                   </a>
                                               @endif
                                           </td>
                                           {{--<td>{{$user->remarks}}</td>--}}
                                           <td>
                                               @if($user->isAdminUser())
                                                   @can('Delete Admin')
                                                        {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route($base_route.'.destroy',$user->user_code),$user,"Delete {$title}",$user->name)!!}
                                                   @endcan
                                               @endif

                                               <div class="dropdown">
                                                   <button class="btn bt btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                       Actions
                                                       <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                   </button>
                                                   <ul style="margin-left:-100px" class="dropdown-menu">
                                                       @if($user->isAdminUser())

                                                        @can('Update Admin')
                                                          <li>  {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'.edit', $user->user_code),"Edit {$title}", 'pencil','primary')!!} </li>
                                                        @endcan


                                                        <li>
                                                          <button type="button" class="btn btn-primary btn-xs change-password-btn"
                                                                   data-user-code="{{$user['user_code']}}">
                                                               Change Password
                                                          </button>
                                                        </li>
                                                       @endif

                                                           @if($user->isBanned())
                                                           <li>
                                                               <a href="{{route('admin.user-account-log.unBannedUser',$user->user_code)}}" class="unban-button"
                                                                  data-user-code="{{$user->user_code}}"
                                                                  data-user-type="{{$user->userType->user_type_name}}"
                                                                  data-user-name="{{$user->name}}"
                                                               >
                                                                   <button class="btn btn-success btn-xs" title="Unban User">
                                                                       Unban
                                                                   </button>
                                                               </a>
                                                           </li>
                                                           @elseif($user->isSuspended())
                                                             <li>
                                                                 <a href="{{route('admin.user-account-log.unSuspendUser',$user->user_code)}}" class="unsuspend-button"
                                                                    data-user-code="{{$user->user_code}}"
                                                                    data-user-type="{{$user->userType->user_type_name}}"
                                                                    data-user-name="{{$user->name}}"
                                                                 >
                                                                    <button class="btn btn-success btn-xs" title="Unsuspend User">
                                                                      Unsuspend
                                                                    </button>
                                                                 </a>
                                                             </li>
                                                           @else
                                                           <li>
                                                               <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#banned-{{$user->user_code}}"  title="Ban User">
                                                                   <span class="fa fa-ban"></span>
                                                                   Ban
                                                               </button>
                                                           </li>

                                                           <li>
                                                              <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#suspend-{{$user->user_code}}"  title="Suspend User">
                                                                <span class="fa fa-gears"></span>
                                                                       Suspend
                                                               </button>
                                                           </li>
                                                           @endif

                                                           <li>
                                                               <a href="{{route('admin.user-account-logs',$user->user_code)}}">
                                                                   <button class="btn btn-primary btn-xs">
                                                                     <span class="fa fa-file"></span>
                                                                     Account Logs
                                                                   </button>
                                                                </a>
                                                           </li>
                                                           <li>
                                                               <a href="{{route('admin.users.show',$user->user_code)}}">
                                                                    <button class="btn btn-primary btn-xs">
                                                                        <span></span>
                                                                         Details
                                                                    </button>
                                                               </a>
                                                           </li>

                                                       <!-- Button trigger modal -->
                                                   </ul>
                                               </div>
                                           </td>
                                       </tr>
                                      @include($module.'.admin.common.banned-reason-modal')
                                      @include($module.'.admin.common.suspend-reason-modal')

                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <p class="text-center"><b>No records found!</b></p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>

                                </table>

                                {{$users->links()}}

                            </div>
                        </div>
                </div>
                @include(''.$module.'.admin.common.user-password-edit-modal')
            </div>
                <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include(''.$module.'.admin.common.user-password-update-script')
    <script>
        $('.unban-button').on('click',function(event){
            event.preventDefault();
            let userCode= $(this).attr('data-user-code');
            let userType = $(this).attr('data-user-type');
            let userName = $(this).attr('data-user-name');
            Swal.fire({
                title: 'Do you Sure you want to Unban User?',
                text:'User Name: '+userName+' | Code: '+userCode+' | Type: '+userType+'',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                width: '500px',
                padding: '10em',
                confirmButtonText: `Yes`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href =($(this).attr('href'));
                }
            });
        });

        $('.unsuspend-button').on('click',function(event){
            event.preventDefault();
            let userCode= $(this).attr('data-user-code');
            let userType = $(this).attr('data-user-type');
            let userName = $(this).attr('data-user-name');
            Swal.fire({
                title: 'Do you Sure you want to Unsuspend User?',
                text:'User Name: '+userName+' | Code: '+userCode+' | Type: '+userType+'',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                width: '500px',
                padding: '10em',
                confirmButtonText: `Yes`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href =($(this).attr('href'));
                }
            });
        });

        $('.user-deactivate').on('click',function(event){
            event.preventDefault();

            let userCode= $(this).attr('data-user-code');
            let userType = $(this).attr('data-user-type');
            let userName = $(this).attr('data-user-name');

            console.log($(this).parent().parent());
            Swal.fire({
                title: 'Do you Sure you want to Deactivate ?',
                text:'User Name: '+userName+' | Code: '+userCode+' | Type: '+userType+'',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                width: '500px',
                padding: '10em',
                confirmButtonText: `Yes`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href =($(this).attr('href'));
                }
            });
        });

        $('.user-activate').on('click',function(event){
            event.preventDefault();

            let userCode= $(this).attr('data-user-code');
            let userType = $(this).attr('data-user-type');
            let userName = $(this).attr('data-user-name');

            Swal.fire({
                title: 'Do you Sure you want to Activate User?',
                text:'User Name: '+userName+' | Code: '+userCode+' | Type: '+userType+'',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                width: '500px',
                padding: '10em',
                confirmButtonText: `Yes`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href =($(this).attr('href'));
                }
            });
        });

    </script>
@endpush
