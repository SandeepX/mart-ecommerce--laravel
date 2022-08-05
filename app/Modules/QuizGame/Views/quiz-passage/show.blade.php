@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
        [
        'page_title'=>$title,
        'sub_title'=> "Show Detail {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')

            <div class="row">
                <!-- left column -->
                <div class="col-md-12">

                    <div class="box box-primary">

                        <div class="box-body">
                            <strong> Quiz Passage Code:{{ $passageDetail->qp_code }}</strong><br><br>
                            <strong>Passage Title : {!! ucfirst($passageDetail->passage_title) !!}</strong><br><br>
                            <strong>Passage : </strong><br>
                                {!! ($passageDetail->passage) !!}<br><br>
                            <strong>Created By: {{  ucfirst($passageDetail->createdBy->name)}} </strong><br><br>
                            <strong>Passage Is Active:
                                @if($passageDetail->passage_is_active==1)
                                    <span class="label label-success">Yes</span>
                                @else
                                    <span class="label label-danger">No</span>
                                @endif
                            </strong><br><br>

                            <strong>Quiz Scheduled Dates:</strong><br>
                           @foreach($passageDetail->quizDates as $key =>$value)
                               <li>{{$value->quiz_passage_date}}</li>
                            @endforeach
                        </div>

                        <div class="box-body">
                            <h3>
                                <strong> List Of Question From Passage Titled : {{ucfirst($passageDetail->passage_title)}}</strong>

                                <div class="pull-right">
                                    <a href="{{route('admin.quiz.passage.question.create',$passageDetail->qp_code)}}" >
                                        <button class="pull-right btn btn-sm btn-success">
                                            <i class="fa fa-plus"></i>
                                            Add more Question
                                        </button>
                                    </a>
                                </div>
                            </h3>




                        </div>

                        <div class="box-body">
                            @foreach($passageDetail->quizQuestions as $key => $value)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>Question {{++$key}}. </strong>  {{ucfirst($value->question)}}<br><br>
                                        <strong>Correct Answer: <label class="label label-info">{{convertToWords($value[$value->correct_answer])}}</label> </strong><br>
                                        <strong>point: <label class="label label-info"> {{$value->points}}</label></strong><br>
                                        <strong>Is Active Question: <label class="label label-info">{{$value->question_is_active===1? 'Yes':'No'}}</label></strong>

                                        <br></br>

                                        <div class="row">
                                            <div class=" col-md-4">
                                                <strong>option A:</strong> {{ucfirst($value->option_a)}}
                                            </div>

                                            <div class=" col-md-6">
                                                <strong>option B:</strong> {{ucfirst($value->option_b)}}
                                            </div>

                                            <div class=" col-md-4">
                                                <strong>option C:</strong> {{ucfirst($value->option_c)}}
                                            </div>

                                            <div class=" col-md-4">
                                                <strong>option D:</strong> {{ucfirst($value->option_d)}}
                                            </div>

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class=" col-md-6">
                                                <a href="{{route('admin.quiz.passage.question-delete',$value->question_code)}}">
                                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Remove</button>
                                                </a>
                                                <a href="{{route('admin.quiz.passage.question.edit',[$value->question_code])}}">
                                                    <button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i> Edit</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection


