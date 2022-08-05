@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{$title}}
                            </h3>


                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route($base_route.'create') }}" style="border-radius: 0px; "
                                   class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{$title}}
                                </a>
                            </div>

                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Job Type</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jobOpenings as $jobOpening)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$jobOpening->title}}</td>
                                        <td>{{$jobOpening->location}}</td>
                                        <td>{{$jobOpening->job_type}}</td>
                                        <td>
                                            @if($jobOpening->is_active)
                                                <span class="label label-success">On</span>
                                            @else
                                                <span class="label label-danger">Off</span>
                                            @endif

                                        </td>

                                        <td>

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'edit', $jobOpening->opening_code),'Edit Job', 'pencil','primary')!!}


                                            {!! \App\Modules\Application\Presenters\DataTable::createDeleteAction('Delete',route($base_route.'destroy',$jobOpening->opening_code),$jobOpening->opening_code,'Job Opening',$jobOpening->opening_code)!!}

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