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
                                List of Product Sensitivities
                            </h3>


                            @can('Create Product Sensitivity')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.product-sensitivities.create') }}"
                                       style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Product Sensitivity
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
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($productSensitivities as $i => $productSensitivity)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$productSensitivity->sensitivity_name}}</td>
                                        <td>{{$productSensitivity->sensitivity_code}}</td>
                                        <td>{{$productSensitivity->remarks}}</td>
                                        <td>

                                            @can('Update Product Sensitivity')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.product-sensitivities.edit', $productSensitivity->sensitivity_code),'Edit Sensitivity', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Product Sensitivity')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.product-sensitivities.destroy',$productSensitivity->sensitivity_code),$productSensitivity,'Sensitivity',$productSensitivity->sensitivity_name)!!}
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