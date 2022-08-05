@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
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

                            @can('Create Slider')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.sliders.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{formatWords($title,false)}}
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
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sliders as $i => $slider)
                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td align="center">
                                            <img src="{{asset($slider->uploadFolder.$slider->slider_image)}}"
                                                 alt="Slider Img" width="100" height="70">
                                        </td>

                                        <td>

                                            @can('Update Slider')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.sliders.edit', $slider->slider_code),'Edit Slider', 'pencil','primary')!!}
                                            @endcan

                                            @can('Delete Slider')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.sliders.destroy',$slider->slider_code),$slider,'Slider','')!!}
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