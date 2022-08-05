@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <section class="content">

            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add More Question In Passage :{{ucfirst($passageDetail->passage_title)}}</h3>
                            @can('View Quiz Passage List')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.quiz.passage.show',$passageDetail->qp_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-backward"></i>
                                       Back
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="addMoreQuestion" action="{{route('admin.quiz.passage.question.add',$passageDetail->qp_code)}}" method="post">
                                @csrf

                                <div class="box-body" id="dynamicForm">

                                    <div id="questionDiv0">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Question 1 :</label>
                                            <div class="col-sm-6">
                                                <input type="text"
                                                       class="form-control" name="quiz[0][question]" required autocomplete="off" placeholder="Enter Quiz Question" value="" />
                                            </div>

                                            <div class="col-sm-2">
                                                <button type="button" class="form-control btn-success btn-xs " id="addMore" >Add More Question</button>
                                            </div>
                                        </div>

                                        <div class="form-group">

                                            <div class="form-horizontal">
                                                <div class="col-md-4 "style="margin-left: 178px;">
                                                    <label class="control-label">option A</label>
                                                    <input  class="form-control " name="quiz[0][option_a]" required autocomplete="off" placeholder="Enter Option A " value="" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="control-label">option B</label>
                                                    <input  class="form-control " name="quiz[0][option_b]" required autocomplete="off" placeholder="Enter Option B" value="" />
                                                </div>
                                            </div>

                                            <div class="form-horizontal">
                                                <div class="col-md-4 " style="margin-left: 178px;">
                                                    <label class=" control-label">option C</label>
                                                    <input  class="form-control " name="quiz[0][option_c]" required autocomplete="off" placeholder="Enter Option C" value="" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label class=" control-label">option D</label>
                                                    <input  class="form-control " name="quiz[0][option_d]" required autocomplete="off" placeholder="Enter Option D" value="" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <label class="col-md-2 control-label">Correct Answer</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="quiz[0][correct_answer]" id="correct_answer">
                                                        <option value="">Select Correct Answer</option>
                                                        <option value="option_a">Option A</option>
                                                        <option value="option_b">Option B</option>
                                                        <option value="option_c">Option C</option>
                                                        <option value="option_d">Option D</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Point :</label>
                                            <div class="col-sm-2">
                                                <input type="number" min="0" class="form-control " name="quiz[0][points]" required autocomplete="off" placeholder="Enter point" value="" />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label  class="col-sm-2 control-label">Question Is Active</label>
                                            <div class="col-sm-6">
                                                <input type="checkbox" class="form-check-input " value="1"  id="question_is_active" name="quiz[0][question_is_active]" checked/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary addMoreQuestionInPassage">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>
    </div>
@endsection


@push('scripts')
    @includeIf('QuizGame::quiz-passage.common.quiz-scripts');
@endpush


