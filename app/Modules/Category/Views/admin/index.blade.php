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
                                List of Categories
                            </h3>


                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route('admin.categories.create') }}" style="border-radius: 0px; "
                                   class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New Category
                                </a>
                            </div>

                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Path</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $i => $category)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$category->category_name}} @if(isset($category->category_icon))<small><img src="{{asset('uploads/categories/icons/'.$category->category_icon)}}"
                                                                                     alt="{{$category->category_icon}}" width="25px" height="25px"></small> @endif</td>
                                        <td>{{$category->category_code}}</td>
                                        <td>{{$category->path}}</td>
                                        <td>
                                            @if(isset($category->category_image))
                                            <img src="{{asset('uploads/categories/images/'.$category->category_image)}}" alt="{{$category->category_image}}" width="50px" height="50px">
                                            @endif
                                        </td>
                                            <td>
                                            @can('Update Category')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.categories.edit', $category->category_code),'Edit Category', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Category')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.categories.destroy',$category->category_code),$category,'Category',$category->category_name)!!}
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
