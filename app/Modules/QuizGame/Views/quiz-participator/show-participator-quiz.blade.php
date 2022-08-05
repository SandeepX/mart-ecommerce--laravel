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
                                List of Participated Quiz
                            </h4>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Quiz Title</th>
                                    <th>Submitted Date </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($participatorQuizDetail as $key => $datum)


                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{($datum->quizPassage) ? ucfirst($datum->quizPassage['passage_title']) :'N/A'}}</td>
                                        <td>{{($datum->submitted_date)}}</td>
                                        <td>
                                            <a href="{{route('admin.quiz.submitted-detail',$datum->quiz_submission_code)}}">
                                                <button style="margin-top: -5px;" type="button" class="btn btn-info btn-xs" >
                                                    <strong>Show Quiz Detail</strong>
                                                </button>
                                            </a>
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
{{--                            {{$participatorQuizDetail->appends($_GET)->links()}}--}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection


