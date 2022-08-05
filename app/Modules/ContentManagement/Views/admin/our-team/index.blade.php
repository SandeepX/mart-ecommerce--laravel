@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.our-teams.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Our Teams
                            </h3>

                            @can('Create Our Teams')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.our-teams.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Our Teams
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Delegation</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ourTeams as $i => $ourTeam)
                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td><img src="{{asset('uploads/contentManagement/our-team/'.$ourTeam->image)}}"
                                                 alt="{{$ourTeam->our_team_code}}" width="50" height="50"></td>
                                        <td>{{$ourTeam->name}}</td>
                                        <td>{{$ourTeam->department}}</td>
                                        <td>{{$ourTeam->delegation}}</td>
                                        <td>{{$ourTeam->is_active == 1? "Active":'Inactive'}}</td>

                                        <td>
                                            @can('Show Our Team')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.our-teams.show', $ourTeam->our_team_code),'Detail', 'eye','info')!!}
                                            @endcan
                                            @can('Update Our Team')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.our-teams.edit', $ourTeam->our_team_code),'Edit Our Team', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Our Team')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.our-teams.destroy',$ourTeam->our_team_code),$ourTeam,'Our Team','')!!}
                                            @endcan


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

