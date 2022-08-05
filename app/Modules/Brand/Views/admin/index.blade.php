@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Brands
                            </h3>

                            @can('Create Brand')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.brands.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Brand
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
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Is Featured</th>
                                    <th>Logo</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($brands as $i => $brand)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$brand->brand_name}}</td>
                                        <td>{{$brand->brand_code}}</td>
                                        <td>{{$brand->is_featured == 1 ? "Yes": "No"}}</td>
                                        <td align="center">
                                            <img src="{{asset('uploads/brand/'.$brand->brand_logo)}}"
                                                 alt="{{$brand->brand_code}}" width="50" height="50">
                                        </td>
                                        <td>{{$brand->remarks}}</td>
                                        <td>
                                            @can('Brand Slider View')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Banner List',route('admin.brand-sliders.index',$brand->brand_code),'Banner Slider List', 'eye','info')!!}
                                            @endcan

                                            @can('Update Brand')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.brands.edit', $brand->brand_code),'Edit Brand', 'pencil','primary')!!}@can('View Department List') @endcan
                                            @endcan

                                            @can('Delete Brand')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.brands.destroy',$brand->brand_code),$brand,'Brand',$brand->brand_name)!!}
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
