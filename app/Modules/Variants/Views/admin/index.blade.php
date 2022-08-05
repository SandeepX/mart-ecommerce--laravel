@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
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
                                List of {{formatWords($title,true)}}
                            </h3>


                            @can('Create Variant')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route("{$base_route}.create") }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{$title}}
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
                                    <th>Variant Values</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($variants as $i => $variant)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$variant->variant_name}}</td>
                                        <td>{{$variant->variant_code}}</td>
                                        <td>{{$variant->remarks}}</td>
                                        <td>{{$variant->variant_values_count}}</td>
                                        <td>

                                            @canany(['Show Variant','Create Variant Value','View Variant Value List',
                                            'Show Variant Value','Update Variant Value','Delete Variant Value'])
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Show + Variant Values',route($base_route.'.show', $variant->variant_code),"Show {$title}", 'eye','info')!!}
                                            @endcanany

                                            @can('Update Variant')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'.edit', $variant->variant_code),"Edit {$title}", 'pencil','primary')!!}
                                            @endcan

                                            @can('Delete Variant')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route($base_route.'.destroy',$variant->variant_code),$variant,"Delete {$title}",$variant->variant_name)!!}
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