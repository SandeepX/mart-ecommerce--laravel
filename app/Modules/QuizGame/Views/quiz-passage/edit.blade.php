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

                            <h3 class="box-title">Update {{$title}}</h3>
                            @can('View Quiz Passage List')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.quiz.passage.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="updateNewPassage"
                                  action="{{route($base_route.'.update',$quizPassageDetail->qp_code)}}"
                                  method="post"
                            >
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Passage Title</label>
                                    <div class="col-sm-6">
                                        <input id="passage_title"
                                               class="form-control "
                                               name="passage_title"
                                               required
                                               autocomplete="off"
                                               value="{{$quizPassageDetail->passage_title}}"
                                        />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Passage</label>
                                    <div class="col-sm-6">
                                        <textarea
                                            id="text"
                                            class="form-control summernote"
                                            name="passage"
                                            required
                                            autocomplete="off"
                                            placeholder="Enter Passage "
                                            value="{{old('passage')}}" >
                                            {{$quizPassageDetail->passage}}
                                        </textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Quiz Date</label>
                                    <div class="col-sm-6">
                                        <div class='input-group date datetimepicker'>
                                            <input type='text'
                                                   autocomplete="off"
                                                   class="form-control"
                                                   value="{{ $quizDates }}"
                                                   name="quiz_passage_date"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label  class="col-sm-2 control-label">Passage Is Active</label>
                                    <div class="col-sm-6">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               value="1"
                                               id="passage_is_active"
                                               name="passage_is_active"
                                               {{(isset($quizPassageDetail) && ($quizPassageDetail->passage_is_active==1))?'checked':''}}/>
                                    </div>
                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary addNewPassage">update</button>
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


