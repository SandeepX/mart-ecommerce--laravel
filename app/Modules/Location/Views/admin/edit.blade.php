@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Edit the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route('admin.location-hierarchies.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">Edit the Location Hierarchy : {{$locationHierarchy->location_name}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.location-hierarchies.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of Location Location Hierarchy
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" action="{{route($base_route.'.update',$locationHierarchy->location_code)}}" enctype="multipart/form-data" method="post">
                                @method('PUT')
                                @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tole/Street Name</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($locationHierarchy) ? $locationHierarchy->location_name : old('location_name')  }}" placeholder="Enter the Tole/Street Name" name="location_name" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tole/Street Devanagari Name</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($locationHierarchy) ? $locationHierarchy->location_name_devanagari : old('location_name_devanagari')  }}" placeholder="Enter the Tole/Street Name in Devanagari" name="location_name_devanagari" required autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label">Tole/Street Code</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($locationHierarchy) ? $locationHierarchy->location_code : old('location_code')  }}" placeholder="Enter the Tole/Street Code" name="location_code" required autocomplete="off">
                                        </div>
                                    </div> -->

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Save</button>
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
