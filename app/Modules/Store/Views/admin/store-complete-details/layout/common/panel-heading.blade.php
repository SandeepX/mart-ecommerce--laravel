<div class="panel-heading">
    <div class="text-left">
        <div class="row" style="font-size: 13px; font-family: Arial, Helvetica, sans-serif;">
            <div class="col-xs-2">
                <i class="fa fa-user" ></i>
                {{($store->store_name)? $store->store_name : 'N/A'}}
            </div>

            <div class="col-xs-3">
                <i class="fa fa-envelope"></i>
                {{($store->store_email)? $store->store_email : 'N/A'}}
            </div>

            <div class="col-xs-2">
                <i class="fa fa-phone"></i>
                {{($store->store_contact_phone)? $store->store_contact_phone : 'N/A'}}
            </div>

            <div class="col-xs-2">
                <i class="fa fa-map-marker"></i>
{{--                {{$store->location->municipality->location_name}},--}}
                {{$store->location->municipality->district->location_name}},
                {{$store->location->location_name}}
            </div>

            <div class="col-xs-2">
                <i class="fa fa-cog"></i> {{$store->store_code}}
            </div>

        </div>

        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
            <a href="{{ route('admin.stores.index') }}" style="border-radius: 0; " class="btn btn-sm btn-primary">
                <i class="fa fa-list"></i> List of Stores
            </a>
        </div>
    </div>
</div>
