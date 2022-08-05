<style>
    .form-group{
        margin-bottom: 6px !important;
    }
    .alert{
        padding: 5px !important;
    }
    .swal-wide{
        width:300px !important;
        height:200px !important;
    }
</style>

<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Store Name </strong>: {{$store->store_name}} ({{$store->store_code}}) <br/>
        <strong>Current Store Type </strong> : {{$store->storeType->store_type_name}} <br/>
        <strong>Store Package Name </strong>  : {{$store->storeTypePackage->package_name}}
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="fromStorePackageUpdate" action="{{route('admin.store.package.update',$store->store_code)}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Store Types</label>
            <select type="text" class="form-control input-sm" value=""  name="store_type_code" id="store_type_code" required autocomplete="off">
                <option value="" disabled selected>Select Store Type</option>
                @foreach($storeTypes as $storeType)
                <option value="{{$storeType->store_type_code}}">{{$storeType->store_type_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">Packages</label>
            <select type="text" name="store_type_package_history_code" class="form-control input-sm" id="store_type_package_code" required autocomplete="off">
                <option value="" disabled selected>Select Packages</option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">Remarks</label>
            <textarea class="form-control input-sm" name="remarks" id="remarks" required></textarea>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveStoreBalanceControl" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

@include('Store::admin.store-package.scripts.store-update-status-scripts')




