@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.vision-mission.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Vission Mission
                            </h3>

                            @can('Create Vision')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.vision-mission.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Vision Mission
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
                                    <th>Vision</th>
                                    <th>Mission</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($visions as $i => $vision)
                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td>{{$vision->vision_description}}</td>
                                        <td>{{$vision->mission_description}}</td>
                                        <td>{{$vision->is_active == 1? "Active":'Inactive'}}</td>

                                        <td>
                                            @can('Show Vision')

                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.vision-mission.show', $vision->vision_code),'Detail', 'eye','info')!!}


                                            @endcan
                                            @can('Update About Us')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.vision-mission.edit', $vision->vision_code),'Edit About Us', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete About Us')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.vision-mission.destroy',$vision->vision_code),$vision,'About Us','')!!}
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
