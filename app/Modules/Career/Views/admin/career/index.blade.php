@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{$title}}
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route($base_route.'create') }}" style="border-radius: 0px; "
                                   class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{$title}}
                                </a>
                            </div>

                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Applied No</th>
                                    <th>Description</th>
                                    <th>Is Active</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($career as $career)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$career->title}}</td>
                                        <td>{{$career->candidates_count}}</td>
                                        <td>{{$career->descriptions}}</td>
                                        <td>
                                            @if($career->is_active)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>

                                            @can('Update Career')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.careers.edit', $career->career_code),'Edit Career', 'pencil','primary')!!}
                                            @endcan

{{--                                            @can('Delete Career')--}}
{{--                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.careers.destroy',$career->career_code),$career,'Career',$career->title)!!}--}}
{{--                                            @endcan--}}

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
