@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Edit the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">Edit the Career : {{$career->title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of Career
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="editCareer" action="{{route($base_route.'update',$career->career_code)}}" enctype="multipart/form-data" method="post">
                                @method('PUT')
                                @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{$career->title }}"
                                                   placeholder="Enter job title" name="title" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="descriptions" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-6">
                                            <textarea id="descriptions" class="form-control" name="descriptions"
                                                      placeholder="Enter job description">{{$career->descriptions}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active" class="col-sm-2 control-label">Active</label>
                                        <div class="col-sm-6">
                                            <input type="hidden" value="0" name="is_active">
                                            <input id="is_active" name="is_active"
                                                   type="checkbox" value="1"
                                                {{( ($career->is_active==1))?'checked':''}}
                                            >
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;"
                                            class="btn btn-block btn-primary">Update
                                    </button>
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

