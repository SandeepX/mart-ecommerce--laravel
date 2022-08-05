

<div class="card card-default bg-panel">
    <div id="collapse1" class="collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group nav">
                        <a href="#vendorDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action vendorList">Vendor Details</a>
                        <a href="#locationDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action vendorList">Location Details</a>
                        <a href="#contactDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action vendorList">Contact Details</a>
                        <a href="#userDetails" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action vendorList">User Details</a>
                        <a href="#doc" data-toggle="tab" aria-expanded="true" class="list-group-item list-group-item-action vendorList">Documents</a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        <div class="tab-pane active" id="vendorDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Vendor Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-3">
                                            <dt>Vendor Name:</dt> {{$vendor->vendor_name}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>Vendor Code:</dt>{{$vendor->vendor_code}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>Company Type:</dt>{{($vendor->companyType) ? $vendor->companyType->company_type_name:'N/A'}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>Vendor Type:</dt>{{($vendor->vendorType) ? $vendor->vendorType->vendor_type_name:'N/A'}}
                                        </div>
                                    </div>

                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Registration Type:</dt>{{($vendor->registrationType)?$vendor->registrationType->registration_type_name:'N/A'}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Vendor Owner:</dt>{{$vendor->vendor_owner}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Vendor Size:</dt>{{($vendor->company_size) ? $vendor->company_size:'N/A'}}
                                        </div>
                                    </div>

                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-3">
                                            <dt>Vendor Created Date:</dt>{{date_format($vendor->created_at,"Y/m/d H:i:s")}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>Pan No:</dt>{{$vendor->pan}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>VAT No:</dt>{{$vendor->vat ? $vendor->vat:'N/A'}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>Vendor Logo:</dt>
                                            @if(isset($vendor->vendor_logo))
                                                <img style="width: 100px;height: 100px" src="{{photoToUrl($vendor->vendor_logo,asset('uploads/vendors/logo/'))}}"
                                                     alt="Vendor Logo">
                                            @else
                                            N/A
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Vendor Status:</dt><span class="label label-{{($vendor->status==1)?'success':'danger'}}">{{($vendor->status==1)?'Active':'Inactive'}}</span>
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
                                            <dt>Province:</dt>{{$vendor->location->municipality->district->province->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>District:</dt>{{$vendor->location->municipality->district->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Municipality:</dt>{{$vendor->location->municipality->location_name}}
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Ward:</dt>{{$vendor->location->location_name}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Land Mark:</dt>{{$vendor->vendor_landmark}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="contactDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> Vendor Contact Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <div class="row" style="margin: 20px 0;">
                                        <div class="col-xs-4">
                                            <dt>Contact Person:</dt>{{$vendor->contact_person}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Contact Landline:</dt>{{$vendor->contact_landline}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Contact Mobile:</dt>{{$vendor->contact_mobile}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Contact Email:</dt>{{$vendor->contact_email}}
                                        </div>
                                        <div class="col-xs-4">
                                            <dt>Contact Fax:</dt>{{$vendor->contact_fax}}
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
                                        <div class="col-xs-3">
                                            <dt>Vendor Owner Name:</dt>{{($vendor->user)?$vendor->user->name:'N/A'}}
                                        </div>
                                        <div class="col-xs-3">
                                            <dt>Vendor Owner Email:</dt>{{($vendor->user)? $vendor->user->login_email:'N/A'}}
                                        </div>

                                        <div class="col-xs-3">
                                            <dt>Vendor Owner Avatar:</dt>
                                            @if(isset($vendor->user))
                                                <img style="width: 100px;height: 100px" src="{{photoToUrl($vendor->user->avatar,asset('uploads/user/avatar/'))}}"
                                                     alt="user avatar">
                                            @else
                                                N/A
                                            @endif
                                        </div>

                                        <div class="col-xs-3">
                                            <dt>Gender:</dt>{{($vendor->user)? ucfirst($vendor->user->gender) : '-'}}
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
                                        @if($vendorDocument)
                                            @foreach($vendorDocument as $key => $value)
                                                <div class="col-xs-4">
                                                    <dt>Document Name:</dt>{{$value->document_name}}
                                                </div>
                                                <div class="col-xs-4">
                                                    <dt>File:</dt>
                                                    <img style="width: 100px;height: 100px" src="{{photoToUrl($value->document_file,asset('uploads/vendors/documents'))}}"
                                                         alt="document file">

                                            @endforeach
                                        @else
                                            'N/A'
                                        @endif
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
