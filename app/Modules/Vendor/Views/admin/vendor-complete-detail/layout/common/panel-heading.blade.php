<div class="panel-heading">
    <div class="text-left">
        <div class="row" style="font-size: 13px; font-family: Arial, Helvetica, sans-serif;">
            <div class="col-xs-2">
                <i class="fa fa-user" ></i>
                {{($vendor->vendor_name)? $vendor->vendor_name : 'N/A'}}
            </div>

            <div class="col-xs-3">
                <i class="fa fa-envelope"></i>
                {{($vendor->contact_email)? $vendor->contact_email : 'N/A'}}
            </div>

            <div class="col-xs-2">
                <i class="fa fa-phone"></i>
                {{($vendor->contact_mobile)? $vendor->contact_mobile : 'N/A'}}
            </div>

            <div class="col-xs-2">
                <i class="fa fa-map-marker"></i>
                {{$vendor->location->municipality->location_name}},
                {{$vendor->location->municipality->district->location_name}},
                {{$vendor->location->location_name}}
            </div>

            <div class="col-xs-2">
                <i class="fa fa-cog"></i> {{$vendor->vendor_code}}
            </div>

        </div>

        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
            <a href="{{route('admin.vendors.index')}}" style="border-radius: 0; " class="btn btn-sm btn-primary">
                <i class="fa fa-list"></i> List of Vendors
            </a>
        </div>
    </div>
</div>
