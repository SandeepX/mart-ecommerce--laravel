@extends('Admin::layout.common.masterlayout')
@push('css')
    <style>
        input[type=checkbox] {
            transform: scale(1.5);
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 4px 10px;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        hr {
            margin-top: 6px;
            margin-bottom: 8px;
            border: 0;
            border-top: 4px solid #eee;
        }

        .text-muted {
            margin-left: 0px;
        }

        .box-title {
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
@endpush


@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=>'Show About Us Details',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.about-us.index'),
    ])
    <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">


                        <div class="box-body">

                            <div class="col-md-12">

                                <div class="card card-default">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <a href="javascript:void(0)">
                                                <b>SECTION I : COMPANY DETAILS</b>
                                            </a>
                                        </h4>
                                        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                            <a href="{{ route($base_route . '.index') }}" style="border-radius: 0px; "
                                               class="btn btn-sm btn-primary">
                                                <i class="fa fa-list"></i>
                                                List of {{ formatWords($title, true) }}
                                            </a>
                                        </div>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Page Image:</label><br>
                                                        <img class="img-responsive" src="{{asset('uploads/contentManagement/page/'.$aboutUs->page_image)}}"
                                                             alt="Vendor Logo" >
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Company Name:</label>
                                                        <p>{{$aboutUs['company_name']}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Company Description:</label>
                                                        <p>{{$aboutUs['company_description']}}</p>
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
                                                <b>SECTION II : CEO DETAILS</b>
                                            </a>
                                        </h4>

                                    </div>
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">CEO Image</label><br>
                                                        <img src="{{asset('uploads/contentManagement/ceo/'.$aboutUs->ceo_image)}}"
                                                             alt="Vendor Logo" width="150" height="150">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">CEO Name</label>
                                                        <p>{{$aboutUs['ceo_name']}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">CEO Message</label>
                                                        <p>{{$aboutUs['message_from_ceo']}}</p>
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
        <!-- /.content -->
    </div>



@endsection
