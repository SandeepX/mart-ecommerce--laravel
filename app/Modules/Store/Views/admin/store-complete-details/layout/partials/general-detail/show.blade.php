

<div class="card card-default bg-panel">
    <div id="collapse1" class="collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group nav">
                        <a href="#storeDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action storeList">Store Details</a>
                        <a href="#locationDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action storeList">Location Details</a>
                        <a href="#contactDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action storeList">Contact Details</a>
                        <a href="#userDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action storeList">User Details</a>
                        <a href="#doc" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action storeList">Documents</a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        <div class="tab-pane active" id="storeDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Store Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Store Name:</dt> {{$store->store_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Store Code:</dt>{{$store->store_code}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Company Type:</dt>{{($store->companyType) ? $store->companyType->company_type_name:'N/A'}}
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Registration Type:</dt>{{($store->registrationType)?$store->registrationType->registration_type_name:'N/A'}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Store Owner:</dt>{{$store->store_owner}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Store Size:</dt>{{($store->storeSize) ? $store->storeSize->store_size_name:'N/A'}}
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Store Established Date:</dt>{{$store->store_established_date}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Pan No:</dt>{{$store->pan_vat_no}}
                                        </div>
                                        <div class="col-xs-4">
{{--                                            <dt>Store Logo:</dt><img src="" alt="">--}}
                                            <dt>Store Logo:</dt>
                                            @if(isset($store->store_logo))
                                                <img style="width: 100px;height: 100px" src="{{photoToUrl($store->store_logo,asset('uploads/stores/logos'))}}"
                                                     alt="Store Logo">
                                            @else
                                            N/A
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Store Status:</dt><span class="label label-success">{{$store->status}}</span>
                                        </div>

                                        @if(isset($warehouse))
                                            <div class="col-xs-4">
                                                <dt>Connected Warehouse:</dt><a href="{{route('admin.warehouses.show', $warehouse->warehouse_code)}}" target="_blank">{{$warehouse->warehouse_name}}</a>
                                            </div>
                                        @endif
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
                                            <dt>Province:</dt>{{$store->location->municipality->district->province->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>District:</dt>{{$store->location->municipality->district->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Municipality:</dt>{{$store->location->municipality->location_name}}
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Ward:</dt>{{$store->location->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Land Mark:</dt>{{$store->store_landmark_name}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="contactDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> Store Contact Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Contact Landline:</dt>{{$store->store_contact_phone}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Contact Mobile:</dt>{{$store->store_contact_mobile}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Contact Email:</dt>{{$store->store_email}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="userDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">User Details</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">

                                            <dt>Store Owner Name:</dt>{{($store->user)?$store->user->name:'N/A'}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Store Owner Email:</dt>{{($store->user)? $store->user->login_email:'N/A'}}
                                        </div>

                                        <div class="col-xs-4">
                                            <dt>Store Owner Avatar:</dt>
                                            @if(isset($store->user))
                                                <img style="width: 100px;height: 100px" src="{{photoToUrl($store->user->avatar,asset('uploads/user/avatar/'))}}"
                                                     alt="user avatar">
                                            @else
                                                N/A
                                            @endif
                                        </div>

                                        <div class="col-xs-4">
                                            <dt>Store Owner Gender:</dt>{{($store->user)? ucfirst($store->user->gender) : '-'}}
                                        </div>

{{--                                        <div class="col-xs-4">--}}
{{--                                            <dt>Manager Code:</dt>{{($store->manager)? ucfirst($store->manager->manager_code) : '-'}}--}}
{{--                                        </div>--}}

                                        <div class="col-xs-4">
                                            <dt>Referred By :</dt>{{($store->referredBy)? ucfirst($store->referredBy->manager->manager_name) : '-'}}
                                        </div>

                                        <div class="col-xs-4">
                                            <dt>Referral User Email:</dt>{{($store->referredBy)? ($store->referredBy->manager->manager_email) : '-'}}
                                        </div>

                                        <div class="col-xs-4">
                                            <dt>Referral User Gender:</dt>{{($store->referredBy)? ucfirst($store->referredBy->manager->user->gender) : '-'}}
                                        </div>



                                    </div>
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
{{--            <div class="row">--}}
{{--                <div class="col-md-12 text-center">--}}
{{--                    <a href="#storeOrder" data-toggle="tab" aria-expanded="true" class="btn btn-primary">Next</a>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>
</div>
