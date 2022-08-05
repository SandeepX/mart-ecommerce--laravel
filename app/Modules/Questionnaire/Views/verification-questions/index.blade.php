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
                                List Of Verification Questions
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.verification-questions.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Verification Questions
                                    </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>AVQ Code</th>
                                    <th>Action</th>
                                    <th>Entity</th>
                                    <th>Question</th>
                                    <th>Is Active</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($verificationQuestions as $verificationQuestion)
                                   <tr>
                                       <td>{{++$loop->index}}</td>
                                       <td>{{$verificationQuestion->avq_code}}</td>
                                       <td>{{ucwords(str_replace('_',' ',$verificationQuestion->action))}}</td>
                                       <td>{{ucwords(str_replace('_',' ',$verificationQuestion->entity))}}</td>
                                       <td>{!! $verificationQuestion->question !!}</td>
                                       <td>{{$verificationQuestion->is_active}}</td>
                                       <td>
                                           {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.verification-questions.edit',$verificationQuestion->avq_code ),'Edit Question', 'pencil','info')!!}
                                           {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.verification-questions.destroy',$verificationQuestion->avq_code ),$verificationQuestion,'Action  Verifications Questions','Questions' )!!}
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

