@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route,$user->user_code),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}} . User Name: {{$user->name}}-({{$user->user_code}})
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Account Log Code</th>
                                    <th>Account Status</th>
                                    <th>Reason</th>
                                    <th>Banned/Suspended By</th>
                                    <th>UnBanned/UnSuspened By</th>
                                    <th>Time For</th>
                                    <th>Started From</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($userAccountLogs as $i => $userAccountLog)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$userAccountLog->user_account_log_code}}</td>
                                        <td> <span class="label @if($userAccountLog->account_status == 'permanently_banned')  label-danger @else label-warning @endif"> {{ ucwords(str_replace('_',' ',$userAccountLog->account_status)) }}</span> </td>
                                        <td>{{$userAccountLog->reason}}</td>
                                        <td>@if(isset($userAccountLog->banned_by)){{ $userAccountLog->bannedBy->name}} @endif</td>
                                        <td>@if(isset($userAccountLog->unbanned_by)) {{$userAccountLog->unBannedBy->name}} @endif</td>
                                        <td>
                                            @if($userAccountLog->is_closed==0)
                                                {{diffDate($userAccountLog->created_at)}}
                                            @else
                                                {{diffDate($userAccountLog->created_at,$userAccountLog->updated_at)}}
                                            @endif
                                        </td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($userAccountLog->created_at))}}</td>
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


                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

