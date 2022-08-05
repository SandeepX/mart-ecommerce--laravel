<div class="panel-heading">
    <div class="text-left">
        <div class="row" style="font-size: 17px; font-family: Arial, Helvetica, sans-serif;">
            <div class="col-xs-2">
                <i class="fa fa-user"></i>
                @if($warehouse->warehouse_name)
                {{$warehouse->warehouse_name}}
                @else
                N/A
                @endif
            </div>
            <div class="col-xs-3">
                <i class="fa fa-envelope"></i>
                @if($warehouse->contact_email)
                    {{$warehouse->contact_email}}
                @else
                    N/A
                @endif

            </div>
            <div class="col-xs-2">
                <i class="fa fa-phone"></i>
                @if($warehouse->contact_phone_2)
                    {{$warehouse->contact_phone_2}}
                @else
                    N/A
                @endif

            </div>
            <div class="col-xs-2">
                <i class="fa fa-map-marker"></i>
                @if($warehouse->landmark_name)
                    {{$warehouse->landmark_name}}
                @else
                    N/A
                @endif
            </div>
            <div class="col-xs-2">
                <i class="fa fa-cog"></i> {{$warehouse->warehouse_code}}
            </div>
        </div>
{{--        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">--}}
{{--            <a href="javascript:void(0)" style="border-radius: 0; " class="btn btn-sm btn-primary">--}}
{{--                <span>Current Balance : Nrs. {{$currentBalance}}</span>--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>
</div>
