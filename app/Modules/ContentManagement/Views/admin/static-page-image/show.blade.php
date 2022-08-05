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
                                Detail Static Image Page : {{ucfirst($showDetail[0]['page_name'])}}
                            </h3>

                            @can('Create Static Page image')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.static-page-images.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                    </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>created at</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($showDetail as $key =>$data)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td><img src="{{asset('uploads/content-management/static-page-images/'.$data['image'])}}" alt="" style="width:100px;height:50px;"></td>
                                        <td>{{date('Y M d',strtotime($data->created_at))}}</td>
                                        <td>

                                            @can('Update static Page Image')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.static-page-images.edit', $data->static_page_image_code),'Edit Page', 'pencil','primary')!!}
                                            @endcan

                                            @can('Delete Static page Image')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.delete-single-Image',$data->static_page_image_code),$data,'ContentManagement','Static page Image' )!!}
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

