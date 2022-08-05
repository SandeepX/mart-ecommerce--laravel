@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$brand->brand_name. " Sliders",
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.brand-sliders.index',$brand->brand_code),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{$brand->brand_name}} Sliders
                            </h3>

                            @can('Create Brand Slider')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.brand-sliders.create',$brand->brand_code) }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Brand Slider
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
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($brandSliders as $i => $brandSlider)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td><img src="{{asset('uploads/brand/slider/'.$brandSlider->image)}}"
                                                 alt="{{$brandSlider->brand_slider_code}}" width="50" height="50"></td>

                                        <td>{{$brandSlider->description}}</td>
                                        <td>{{$brandSlider->is_active == 1? "Active":'Inactive'}}</td>
                                        <td>
                                            @can('Show Brand Slider')

                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.brand-sliders.show',$brand->brand_code ."/". $brandSlider->brand_slider_code),'Detail', 'eye','info')!!}


                                            @endcan
                                            @can('Update Brand Slider')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.brand-sliders.edit',$brand->brand_code."/". $brandSlider->brand_slider_code),'Edit Brand Slider', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Brand Slider')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.brand-sliders.destroy',$brandSlider->brand_slider_code),$brandSlider,'Brand Slider','')!!}
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
