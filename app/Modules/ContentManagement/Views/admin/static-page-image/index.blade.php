@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.static-page-images.index'),
    ])


    <!-- Main content -->
        <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">


                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Static Page with latest Image
                            </h3>

                            @can('Create Static Page image')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.static-page-images.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                       Add new static page image
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Page Name</th>
                                    <th>Image</th>
                                    <th>Latest created at</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($staticPageImage as $key =>$data)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$data['page_name']}}</td>
                                        <td><img src="{{asset('uploads/content-management/static-page-images/'.$data['image'])}}" alt="" style="width:100px;height:50px;"></td>
                                        <td>{{date('Y M d',strtotime($data->created_at))}}</td>
                                        <td>

                                            @can('Show static Page Image')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.static-page-images.show',$data->page_name ),'View All images of this Page', 'eye','primary')!!}
                                            @endcan

                                            @can('Delete Static page Image')

                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.delete-All-Image',$data->page_name),$data,'ContentManagement','Static page Image' )!!}
                                            @endcan
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
                            {{$staticPageImage->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>



@endsection
