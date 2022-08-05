
<div class="card card-default bg-panel">
    <div id="collapse1" class="collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group nav" id="general-detail-tabs">
                        <a href="#warehouseDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action warehouseList">Warehouse Details</a>
                        <a href="#locationDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action warehouseList">Location Details</a>
                        <a href="#contactDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action warehouseList">Contact Details</a>
                        <a href="#changePassword" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action warehouseList">Change Password</a>
                        {{--                        <a href="#userDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action warehouseList">User Details</a>--}}
                        <a href="#doc" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action warehouseList">Documents</a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        <div class="tab-pane active" id="warehouseDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Warehouse Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Warehouse Name:</dt> {{$warehouse->warehouse_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Warehouse Code:</dt> {{$warehouse->warehouse_code}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Warehouse Type:</dt>
                                            @if(isset($warehouse->warehouseType->warehouse_type_name))
                                                {{$warehouse->warehouseType->warehouse_type_name}}
                                            @else
                                            N/A
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Remarks:</dt>
                                            @if(isset($warehouse->remarks))
                                            {{$warehouse->remarks}}
                                            @else
                                            N/A
                                            @endif
                                        </div>

                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Pan No:</dt>{{$warehouse->pan_vat_no}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Warehouse Logo:</dt>
                                            @if(isset($warehouse->warehouse_logo))
                                                <img src="{{$warehouse->warehouse_logo}}"
                                                     alt="Warehouse Logo" width="100" height="60" >
                                            @else
                                                 N/A
                                            @endif

                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="locationDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Location Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Province:</dt>{{$warehouse->location->municipality->district->province->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>District:</dt>{{$warehouse->location->municipality->district->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Municipality:</dt>{{$warehouse->location->municipality->location_name}}
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Ward:</dt>{{$warehouse->location->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Land Mark:</dt>{{$warehouse->landmark_name}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="contactDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Contact Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Contact Name:</dt>{{$warehouse->contact_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Warehouse Contact Phone 1:</dt></dt>{{$warehouse->contact_phone_1}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Warehouse Contact Phone 2:</dt></dt>{{$warehouse->contact_phone_2}}
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Contact Email:</dt>{{$warehouse->contact_email}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

{{--                        <div class="tab-pane" id="userDetails">--}}
{{--                            <div class="box box-solid">--}}
{{--                                <div class="box-header with-border">--}}
{{--                                    <h3 class="box-title">User Details</h3>--}}
{{--                                </div>--}}
{{--                                <!-- /.box-header -->--}}
{{--                                <div class="box-body text-left">--}}
{{--                                    <div class="row" style="margin: 20px 0;">--}}
{{--                                        <div class="col-xs-4">--}}
{{--                                            <dt>User Name:</dt>{{$warehouse->contact_name}}--}}
{{--                                        </div>--}}
{{--                                        <div class="col-xs-4">--}}
{{--                                            <dt>Contact Email:</dt>{{$warehouse->contact_email}}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <!-- /.box-body -->--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="tab-pane" id="changePassword">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Change Password</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    @include('AlpasalWarehouse::admin.warehouse-complete-details.layout.partials.general-detail.warehouse-password')
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>


                        <div class="tab-pane" id="doc">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Document(s)</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Document Name:</dt>Citizenship
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>File:</dt>.....
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
