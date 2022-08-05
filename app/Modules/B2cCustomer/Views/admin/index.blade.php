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
    'manage_url'=>route($base_route.'.index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.b2c-user.index')}}" method="get">

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
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Active</th>
                                    <th>Created At</th>
                                    <th>Status</th>
{{--                                    <th>Action</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($userB2C as $i => $user)

                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td>
                                            @can('Show Customer')
                                            <a href="{{route('admin.b2c-user.show',$user->user_code)}}"><b>{{ucfirst($user->name)}}</b></a>
                                            @endcan
                                        </td>

                                        <td>{{$user->login_email}}</td>

                                        <td>
                                            {{$user->login_phone}}

                                            @if($user->is_phone_verified == 1)
                                                <i class="fa fa-check-square" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            @endif
                                        </td>

                                        <td>
                                            @if($user->is_active==1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{getReadableDate(getNepTimeZoneDateTime($user->responded_at),'Y-M-d')}}</td>


                                        <td> <span class="label label-primary">
                                                {{ucfirst($user->userB2CRegistrationStatus->status)}}
                                            </span>
                                        </td>


{{--                                        <td>--}}
{{--                                            <a href="">--}}
{{--                                                <button type="button" class="btn btn-primary btn-xs ">Change Password</button>--}}
{{--                                            </a>--}}

{{--                                        </td>--}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <p class="text-center"><b>No records found!</b></p>
                                        </td>
                                    </tr>

                                    <!-- /.row -->

                                @endforelse
                                </tbody>
                            </table>
                            {{$userB2C->links()}}
                        </div>
                    </div>
                </div>

            </div>


        </section>
        <!-- /.content -->
    </div>

@endsection



