@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">

    @include('Admin::layout.partials.flash_message')

    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=>'Show Manager Diary Details',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index',$managerDiary->manager_code),
    ])
    <!-- Main content -->
        <section class="content">

            <div class="row">
                <div id="showFlashMessage"></div>
                <br>
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="card card-default">
                                    <div id="collapse2" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager Diary Code</label>
                                                        <p>{{$managerDiary->manager_diary_code}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manager</label>
                                                        <p>{{$managerDiary->manager->manager_name}} ({{$managerDiary->manager_code}})</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Store Name</label>
                                                        <p>{{$managerDiary->store_name}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Referred Store</label>
                                                        <p>{{ isset($managerDiary->referredStore) ? $managerDiary->referredStore->store_name .' ('.$managerDiary->referred_store_code.')' : NULL}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Owner Name</label>
                                                        <p>{{$managerDiary->owner_name}}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Phone No</label>
                                                        <p>{{$managerDiary->phone_no}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Alt Phone No</label>
                                                        <p>{{$managerDiary->alt_phone_no}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Location</label>
                                                        <p>{{$managerDiary->full_location}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Investment Amount</label>
                                                        <p>{{getNumberFormattedAmount($managerDiary->business_investment_amount)}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Created At</label>
                                                        <p>{{getReadableDate(getNepTimeZoneDateTime($managerDiary->created_at))}}</p>
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
