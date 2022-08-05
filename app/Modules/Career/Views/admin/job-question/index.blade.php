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
                                List of {{$title}}
                            </h3>



                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route($base_route.'.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{$title}}
                                </a>
                            </div>

                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jobQuestions as $jobQuestion)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$jobQuestion->question}}</td>

                                        <td>

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'.edit', $jobQuestion->question_code),'Edit Question', 'pencil','primary')!!}


                                            {!! \App\Modules\Application\Presenters\DataTable::createDeleteAction('Delete',route($base_route.'.destroy',$jobQuestion->question_code),$jobQuestion->question_code,'Job Question',$jobQuestion->question_code)!!}

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