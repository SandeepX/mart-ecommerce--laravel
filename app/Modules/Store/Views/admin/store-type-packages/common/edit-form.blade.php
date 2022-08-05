
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Edit Store Type Package - {{$storeTypePackage->package_name}}
    </h4>
    <div id="showFlashMessageModal"></div>
</div>
<form method="post" action="{{route($base_route.'.update',$storeTypePackage->store_type_package_master_code)}}" id="form" enctype="multipart/form-data">
    {{csrf_field()}}

    {{ method_field('PUT') }}
    <div class="modal-body pb-0">
        <div class="row">
        <div class="form-group col-md-4">
            <label class="control-label">Package Name</label>
            <div>
                <input type="text" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->package_name : old('package_name')  }}" placeholder="Enter the Package Name" name="package_name" required autocomplete="off">
            </div>
        </div>

{{--        <div class="form-group col-md-4">--}}
{{--            <label  class="control-label">Image</label>--}}
{{--            <div>--}}
{{--                <input type="file" class="form-control" name="image" {{ !isset($storeTypePackage) ? 'required' : ''  }} >--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @if(isset($storeTypePackage->image))--}}
{{--        <div class="col-md-4 mb-0">--}}
{{--            <div class="mt-4 pt-7" style="margin-top: 20px;">--}}
{{--                <img src="{{asset('uploads/stores/storetypepackages/images/'.$storeTypePackage->image)}}" alt="{{$storeTypePackage->package_name}}" width="50px" height="50px">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @endif--}}
        </div>

        <div class="row">
        <div class="form-group col-md-4">
            <label class="control-label">Refundable Registration Charge</label>
            <div>
                <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->refundable_registration_charge : old('refundable_registration_charge')  }}" placeholder="Enter the Refundable Registration Charge" name="refundable_registration_charge" required autocomplete="off">
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Non Refundable Registration Charge</label>
            <div>
                <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->non_refundable_registration_charge : old('non_refundable_registration_charge')  }}" placeholder="Enter the Non Refundable Registration Charge" name="non_refundable_registration_charge" required autocomplete="off">
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Base Investment</label>
            <div>
                <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->base_investment : old('base_investment')  }}" placeholder="Enter the Base Investment" name="base_investment" required autocomplete="off">
            </div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
            <label class="control-label">Annual Purchasing Limit</label>
            <div>
                <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->annual_purchasing_limit : old('annual_purchasing_limit')  }}" placeholder="Enter the Annual Purchasing Limit" name="annual_purchasing_limit" required autocomplete="off">
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Referal Registration Incentive Amount</label>
            <div>
                <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->referal_registration_incentive_amount : old('referal_registration_incentive_amount')  }}" placeholder="Enter the Referal Registration Incentive Amount" name="referal_registration_incentive_amount" required autocomplete="off">
            </div>
        </div>
        <div class="form-group col-md-4">
            <label  class="control-label">Description</label>
            <div>
                <textarea class="form-control" name="description" required>{{isset($storeTypePackage) ? $storeTypePackage->description : old('description')  }}</textarea>
            </div>
        </div>
        </div>
        <input type="hidden" class="form-control" value="{{$storeTypePackage->store_type_code}}" name="store_type_code" required autocomplete="off">

    </div>
    <div class=" text-center" style="padding: 10px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        @if(isset($storeTypePackage) && $storeTypePackage->count())
                <button type="submit" class="btn btn-primary update-pre-order-target">Update</button>
        @endif
    </div>
</form>

