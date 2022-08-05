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
                            <h4 class="panel-title">
                                Submitted Quiz Detail :
                                {{$submittedQuizDetail[0]->quizSubmission->quizPassage->passage_title}}
                            </h4>


                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.quiz.participator.quiz-detail',$submittedQuizDetail[0]->quizSubmission->participator_code)}}"
                                   style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-arrow-circle-left"></i>
                                   Back
                                </a>
                            </div>

                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Submitted Answer </th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($submittedQuizDetail as $key => $datum)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ucfirst($datum->question)}}</td>
                                        <td>{{ucfirst($datum->answer)}}</td>
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

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection


