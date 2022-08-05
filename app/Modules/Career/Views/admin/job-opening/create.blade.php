@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add A {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{$title}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" action="{{route($base_route.'store')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{old('title')  }}" placeholder="Enter job title" name="title" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Location</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{old('location')  }}" placeholder="Enter job location" name="location" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-6">
                                            <textarea id="description" class="form-control" name="description" placeholder="Enter job description">{{old('description')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="requirements" class="col-sm-2 control-label">Requirements</label>
                                        <div class="col-sm-6">
                                            <textarea id="requirements" class="form-control" name="requirements" placeholder="Enter job requirements">{{old('requirements')}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="salary" class="col-sm-2 control-label">Salary</label>
                                        <div class="col-sm-2">
                                            <input id="salary" type="text" class="form-control" value="{{old('salary')}}" placeholder="Enter job salary" name="salary">
                                        </div>

                                        <label for="job_type" class="col-sm-2 control-label">Job Type</label>

                                        <div class="col-sm-2">
                                            <select id="job_type" name="job_type" class="form-control">
                                                @foreach($jobTypes as $key=>$jobType)
                                                    <option value={{$jobType}}
                                                            {{old('job_type') == $jobType ? 'selected' : ''}}>
                                                        {{$key}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Include Job Questions</label>
                                        <div class="col-sm-6">
                                            @foreach($jobQuestions as $index=>$jobQuestion)
                                                <input type="checkbox" id="{{$jobQuestion->question_code}}" name ="job_question_code[]" value="{{$jobQuestion->question_code}}"
                                                       class="all-job-questions"
                                                        {{ is_array(old('job_question_code')) && in_array($jobQuestion->question_code, old('job_question_code')) ? 'checked' : '' }}/>
                                                <label for = "{{$jobQuestion->question_code}}">{{$jobQuestion->question}}</label>

                                                <input id="pr-{{$jobQuestion->question_code}}" type="number" name="question_priority[]" min="1" step="1" class="form-control"
                                                       value="{{old('question_priority.'.$index)}}" placeholder="Question Position">
                                            @endforeach

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active" class="col-sm-2 control-label">Active</label>
                                        <div class="col-sm-6">
                                            <input id="is_active" name="is_active" type="checkbox" {{old('is_active') ? 'checked' :''}}>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Add</button>
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
