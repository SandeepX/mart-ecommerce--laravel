@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title.' Brands',
    'sub_title'=> "Manage ".$title.' Brands',
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
                                List of Categories With Brands
                            </h3>


                            @can('Create Category Brand')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.categories.brands.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Category Brands
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
                                    <th>Category Name</th>
                                    <th>Brands</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $i => $category)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$category->category_name}}</td>
                                        <td>
                                            @php
                                                $brands = $category->brands;
                                                $count = $category->brands->count();
                                                $more = ($count >3) ? ' & '.($count -3).' more Brands' : '';
                                            @endphp

                                            @foreach ($brands->take(3) as $brand)
                                                {{ $brand->brand_name}}
                                                @if(!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach

                                            @if($count >=3)
                                                {{ $more }}
                                            @endif
                                        </td>
                                        <td>
                                            @can('Update Category Brand')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.categories.brands.edit', $category->category_code),'Edit', 'pencil','primary')!!}
                                            @endcan

                                            @can('Show Category Brand')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.categories.brands.show', $category->category_code),'Show', 'eye','info')!!}
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

