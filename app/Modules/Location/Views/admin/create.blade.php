@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "Create {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.location-hierarchies.index'),
    ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add Location</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.location-hierarchies.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of locations
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="alert"  id="showFlashMessage">

                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="locationHierarchyForm" action="" enctype="multipart/form-data">
                                {{csrf_field()}}

                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="location_type" class="col-sm-2 control-label">Location Type</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="location_type" name="location_type" required >
                                                <option selected value="" disabled>--Select An Option--</option>
                                                @foreach ($locationTypes as $locationType)
                                                    <option value={{ $locationType }}>{{ucwords($locationType)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="province" class="col-sm-2 control-label">Province</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="province" name="province" required >
                                                <option selected value="" >--Select An Option--</option>
                                                @foreach ($provinces as $province)
                                                    <option value={{ $province->location_code }}>{{ $province->location_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="district-div" hidden>
                                        <label for="district" class="col-sm-2 control-label">District</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="district" name="district" required >
                                                <option selected value="" >--Select An Option--</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="municipality-div" hidden>
                                        <label for="municipality" class="col-sm-2 control-label">Municipality</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="municipality" name="municipality" required >
                                                <option selected value="" >--Select An Option--</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--<div class="form-group">
                                        <label for="ward" class="col-sm-2 control-label" >Ward</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="ward" required name="ward" >
                                                <option selected value="" >--Select An Option--</option>
                                            </select>
                                        </div>
                                    </div>--}}

                                    <div class="form-group">
                                        <label for="location_name" class="col-sm-2 control-label">Location Name</label>
                                        <div class="col-sm-6">
                                            <input id="location_name" type="text" class="form-control" value="{{old('location_name')  }}" placeholder="Enter the name" name="location_name" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="location_name_devanagari" class="col-sm-2 control-label">Devanagari Name</label>
                                        <div class="col-sm-6">
                                            <input id="location_name_devanagari" type="text" class="form-control" value="{{old('location_name_devanagari')  }}" placeholder="Enter name in Devanagari" name="location_name_devanagari" required autocomplete="off">
                                        </div>
                                    </div>


                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button id="add" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Add</button>
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

@section('script_blades')
    @include(''.$module.'.admin.script')
@endsection


