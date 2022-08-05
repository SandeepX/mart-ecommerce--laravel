@extends('Admin::layout.common.masterlayout')
@push('css')
    <style>
        .candidate_coverLetter{
            border: 1px solid #edeaea;
            border-radius: 4px;
            padding: 5px 5px;
            min-height: 80px;
        }
    </style>
    @endPush
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> "Show the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box">
                        <div class="box-header with-border">

                            <h3 class="box-title">Details of {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
{{--                            <div class="col-md-12">--}}
{{--                                <div class="card">--}}
{{--                                    <!-- /.card-header -->--}}
{{--                                    <div class="card-body">--}}
{{--                                        <h4>Career : {{$candidate->careers->title}}</h4>--}}
{{--                                        <h4>Name : {{$candidate->name}}</h4>--}}
{{--                                        <h4>Email : {{$candidate->email}}</h4>--}}
{{--                                        <h4>Phone Number : {{$candidate->phone_number}}</h4>--}}
{{--                                        <h4>Gender : {{$candidate->gender}}</h4>--}}
{{--                                        <h4>Cover letter :</h4><p> {{$candidate->cover_letter}}</p>--}}
{{--                                        <h4>--}}
{{--                                            Documents:--}}

{{--                                                    <h5>--}}

{{--                                                        <a href="{{asset(\App\Modules\Career\Models\Candidate::IMAGE_PATH.$candidate->cv_file)}}" target="_blank">--}}
{{--                                                            {{$candidate->cv_file}}--}}
{{--                                                        </a>--}}
{{--                                                    </h5>--}}

{{--                                        </h4>--}}

{{--                                    </div>--}}
{{--                                    <!-- /.card-body -->--}}
{{--                                </div>--}}
{{--                                <!-- /.card -->--}}

{{--                            </div>--}}

                            <div class="col-md-12">

                                <div class="card card-default">

                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>SECTION I : Career</b>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Title: </label>
                                                        <span> {{$candidate->careers->title}}</span>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">

                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>SECTION II : Candidate Details</b>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Name: </label>
                                                        <span> {{$candidate->name}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Email: </label>
                                                        <span> {{$candidate->email}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Phone Number: </label>
                                                        <span> {{$candidate->phone_number}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Gender: </label>
                                                        <span> {{$candidate->gender}}</span>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">

                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>SECTION III : Cover Letter</b>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <p class="candidate_coverLetter"> {{$candidate->cover_letter}}</p>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">

                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>SECTION IV : Curriculum vitae</b>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">

                                                        <span> <a href="{{asset(\App\Modules\Career\Models\Candidate::IMAGE_PATH.$candidate->cv_file)}}" target="_blank">
                                                        {{$candidate->cv_file}}
                                                    </a></span>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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

