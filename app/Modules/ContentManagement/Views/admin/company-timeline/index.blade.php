@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.company-timeline.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of About Us
                            </h3>

                            @can('Create Company Timeline')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.company-timeline.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Company Timeline
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
                                    <th>Year</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($companyTimelines as $i => $companyTimeline)
                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td>{{$companyTimeline->year}}</td>
                                        <td>{{$companyTimeline->title}}</td>
                                        <td>{{$companyTimeline->description}}</td>
                                        <td>{{$companyTimeline->is_active == 1? "Active":'Inactive'}}</td>

                                        <td>
                                            @can('Show Company Timeline')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.company-timeline.show', $companyTimeline->company_timeline_code),'Detail', 'eye','info')!!}
                                            @endcan
                                            @can('Update Company Timeline')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.company-timeline.edit', $companyTimeline->company_timeline_code),'Edit About Us', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Company Timeline')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.company-timeline.destroy',$companyTimeline->company_timeline_code),$companyTimeline,'Company Timeline','')!!}
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

