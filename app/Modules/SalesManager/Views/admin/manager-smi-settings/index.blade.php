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
                                List of Manager SMI Settings
                            </h3>


                            @can('Create Manager SMI Settings')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.manager-smi-setting.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New SMI Setting
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Salary(Rs.)</th>
                                    <th>Terms And Conditions</th>
                                    <th>Created By </th>
                                    <th>Created At </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($managerSMISetting as $key => $datum)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{number_format(($datum->salary))}}</td>
                                        <td> {{substr(ucfirst((strip_tags($datum->terms_and_condition))),0,20)}}</td>
                                        <td> {{ucfirst($datum->createdBy->name)}}</td>
                                        <td> {{ date('d-M-Y',strtotime($datum['created_at']))}}</td>
                                        <td>
                                            @can('Show Manager SMI Setting')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.manager-smi-setting.show',$datum->msmi_settings_code ),'Detail', 'eye','primary')!!}
                                            @endcan

                                            @can('Update Social Media')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.manager-smi-setting.edit',$datum->msmi_settings_code ),'Edit Manager SMI Setting', 'pencil','warning')!!}
                                            @endcan

                                            @can('Delete Manager SMI Setting')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.manager-smi-setting.destroy',$datum->msmi_settings_code ),$datum,$datum->msmi_settings_code,'Manager SMI Setting' )!!}
                                            @endcan
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <p class="text-center"><b>No records found!</b></p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            {{$managerSMISetting->appends($_GET)->links()}}


                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection



