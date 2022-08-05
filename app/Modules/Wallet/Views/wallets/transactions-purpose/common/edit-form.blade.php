<div class="form-group">
    <label class="col-sm-2 control-label">Purpose</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ $walletTransactionPurpose->purpose ?? old('purpose')}}" style="text-transform: capitalize;" placeholder="Enter Transaction Purpose Eg: sales,preorder" name="purpose"  autocomplete="off" required>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Purpose Type</label>
    <div class="col-sm-6">
        <select class="form-control" name="purpose_type" required>
            <option value="" disabled>Please Select</option>
            <option value="increment" {{($walletTransactionPurpose->purpose_type == 'increment') ? 'selected' : '' }}>Increment</option>
            <option value="decrement" {{($walletTransactionPurpose->purpose_type == 'decrement') ? 'selected' : '' }}>Decrement</option>
        </select>
    </div>
</div>


<div class="form-group" id="userTypeField">
    <label for="user_type_code" class="col-sm-2 control-label">User Type</label>
    <div class="col-sm-6">
        <select id="user_type_id" name="user_type_code" class="form-control user-type-list select2" required>
            <option value="">Please Select</option>
            @foreach($userTypes as $userType)
                <option value={{$userType->user_type_code}}  {{($walletTransactionPurpose->user_type_code == $userType->user_type_code) ? 'selected' : '' }}>
                    {{$userType->user_type_name}}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active"  value="1" {{$walletTransactionPurpose->is_active ? 'checked' : '' }}>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Admin Control</label>
    <div class="col-sm-6">
        <input type="hidden" name="admin_control" value="0">
        <input type="checkbox" name="admin_control"  value="1" {{$walletTransactionPurpose->admin_control ? 'checked' : '' }}>

    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Close for Modification</label>
    <div class="col-sm-6">
        <input type="hidden" name="close_for_modification" value="0">
        <input type="checkbox" name="close_for_modification"  value="1" {{$walletTransactionPurpose->close_for_modification ? 'checked' : '' }}>

    </div>
</div>



















