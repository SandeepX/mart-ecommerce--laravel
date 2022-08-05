
@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Edit {$title} ",
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

                            <h3 class="box-title">Edit Quiz Question</h3>
                                @can('View Quiz Passage List')
                                    <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                        <a href="{{ route('admin.quiz.passage.show',$quizQuestionDetail->qp_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                            <i class="fa fa-backward"></i>
                                            Back
                                        </a>
                                    </div>
                                @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="updateQuestion" action="{{route($base_route.'.question.update',$quizQuestionDetail->question_code)}}"  method="post">
                                @method('put')
                                @csrf

                                <div class="box-body" id="dynamicForm">

                                    <div id="questionDiv0">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Question 1 :</label>
                                            <div class="col-sm-6">
                                                <input type="text"
                                                       class="form-control" name="question"
                                                       required
                                                       autocomplete="off"
                                                       value="{{$quizQuestionDetail->question}}" />
                                            </div>
                                        </div>

                                        <div class="form-group">

                                            <div class="form-horizontal">
                                                <div class="col-md-4 "style="margin-left: 178px;">
                                                    <label class="control-label">option A</label>
                                                    <input  class="form-control " name="option_a" required autocomplete="off" placeholder="Enter Option A " value="{{$quizQuestionDetail->option_a}}" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="control-label">option B</label>
                                                    <input  class="form-control " name="option_b" required autocomplete="off" placeholder="Enter Option B" value="{{$quizQuestionDetail->option_b}}" />
                                                </div>
                                            </div>

                                            <div class="form-horizontal">
                                                <div class="col-md-4 " style="margin-left: 178px;">
                                                    <label class=" control-label">option C</label>
                                                    <input  class="form-control " name="option_c" required autocomplete="off" placeholder="Enter Option C" value="{{$quizQuestionDetail->option_c}}" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label class=" control-label">option D</label>
                                                    <input  class="form-control " name="option_d" required autocomplete="off" placeholder="Enter Option D" value="{{$quizQuestionDetail->option_d}}" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <label class="col-md-2 control-label">Correct Answer</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="correct_answer" id="correct_answer">
                                                        <option value="">Select Correct Answer</option>
                                                        <option value="option_a" {{($quizQuestionDetail->correct_answer == 'option_a')?'selected':''}}>Option A</option>
                                                        <option value="option_b" {{($quizQuestionDetail->correct_answer == 'option_b')?'selected':''}}>Option B</option>
                                                        <option value="option_c" {{($quizQuestionDetail->correct_answer == 'option_c')?'selected':''}}>Option C</option>
                                                        <option value="option_d" {{($quizQuestionDetail->correct_answer == 'option_d')?'selected':''}}>Option D</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Point :</label>
                                            <div class="col-sm-2">
                                                <input type="number" min="0" class="form-control " name="points"
                                                       required
                                                       autocomplete="off"
                                                       placeholder="Enter point"
                                                       value="{{$quizQuestionDetail->points}}" />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label  class="col-sm-2 control-label">Question Is Active</label>
                                            <div class="col-sm-6">
                                                <input type="checkbox" class="form-check-input "
                                                       value="1"
                                                       id="question_is_active"
                                                       name="question_is_active"
                                                       {{$quizQuestionDetail->question_is_active === 1 ?'checked':''}}/>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary ">Update </button>
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
   <script>

       $('#updateQuestion').submit(function (e, params) {
           var localParams = params || {};
           if (!localParams.send) {
               e.preventDefault();
           }
           Swal.fire({
               title: 'Are you sure you want to update  Quiz Question  ?',
               showCancelButton: true,
               confirmButtonText: `Yes`,
               padding:'10em',
               width:'500px',
               allowOutsideClick:false

           }).then((result) => {
               if (result.isConfirmed) {

                   $(e.currentTarget).trigger(e.type, { 'send': true });
                   Swal.fire({
                       title: 'Please wait...',
                       hideClass: {
                           popup: ''
                       }
                   })
               }
           })
       });
   </script>
@endpush









