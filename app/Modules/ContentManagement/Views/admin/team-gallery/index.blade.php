@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.team-gallery.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Team Gallery
                            </h3>

                            @can('Create Team Gallery')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.team-gallery.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Team Gallery
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
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($teamGalleries as $i => $teamGallery)
                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td><img src="{{asset('uploads/contentManagement/team-gallery/'.$teamGallery->image)}}"
                                                 alt="{{$teamGallery->team_gallery_code}}" width="50" height="50"></td>

                                        <td>{{$teamGallery->is_active == 1? "Active":'Inactive'}}</td>

                                        <td>
                                            @can('Show Team Gallery')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.team-gallery.show', $teamGallery->team_gallery_code),'Detail', 'eye','info')!!}
                                            @endcan
                                            @can('Update Team Gallery')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.team-gallery.edit', $teamGallery->team_gallery_code),'Edit Team Gallery', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Team Gallery')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.team-gallery.destroy',$teamGallery->team_gallery_code),$teamGallery,'Team Gallery','')!!}
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

