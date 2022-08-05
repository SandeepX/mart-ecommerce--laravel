@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> "Show the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <section class="content">
            <div class="row">
            @can('Show Variant')
                <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box">
                            <div class="box-header with-border">

                                <h3 class="box-title">Details of {{$title}} : {{$variant->variant_name}}</h3>

                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route($base_route.'.index') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>

                            </div>

                            <!-- /.box-header -->
                            @include("Admin::layout.partials.flash_message")
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="card">
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <h4>Name : {{$variant->variant_name}}</h4>
                                            <h4>Code : {{$variant->variant_code}}</h4>
                                            <h4>Remarks : {{$variant->remarks}}</h4>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>
                    <!--/.col (left) -->
                @endcan


                <div class="col-md-12">

                @php
                    $variantValueBaseRoute = 'admin.variant-values';
                @endphp
                <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">List of Variant Values</h3>


                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                @can('Create Variant Value')
                                    <button data-toggle="modal"
                                            style="border-radius: 0px; " class="btn btn-sm btn-info"
                                            data-target="#createVariantValueModal"
                                    >
                                        <i class="fa fa-list"></i>
                                        Add New Variant Value
                                    </button>
                                @endcan

                                @include('Variants::admin.variant-values.create',[
                                     'targetModalID'=>'createVariantValueModal',
                                     'variant'=> $variant
                                ])
                            </div>

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="data-table" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($variant->variantValues as $i => $variantValue)
                                                <tr>
                                                    <td>{{++$i}}</td>
                                                    <td>{{$variantValue->variant_value_name}}</td>
                                                    <td>{{$variantValue->variant_value_code}}</td>
                                                    <td>
                                                        @can('Update Variant Value')
                                                            <button data-toggle="modal"
                                                                    style="border-radius: 0px; "
                                                                    class="btn btn-xs btn-primary"
                                                                    data-target="#editVariantValueModal{{$variantValue->id}}"
                                                            >
                                                                <i class="fa fa-pencil"></i>
                                                                Edit
                                                            </button>
                                                        @endcan

                                                        @can('Delete Variant Value')
                                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.variant-values.destroy',$variantValue->variant_value_code),$variantValue,"Delete Variant Value",$variantValue->variant_value_name)!!}
                                                        @endcan


                                                    </td>
                                                </tr>

                                                @include('Variants::admin.variant-values.edit',[
                                                'targetModalID'=>'editVariantValueModal',
                                                'variantValue' => $variantValue
                                                ])

                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection

