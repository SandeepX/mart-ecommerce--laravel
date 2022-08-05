    <div class="form-group">
        <label class="col-sm-2 control-label">Purpose</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" value="{{old('purpose')}}" style="text-transform: capitalize;" placeholder="Enter Transaction Purpose Eg: sales,preorder" name="purpose"  autocomplete="off" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Purpose Type</label>
        <div class="col-sm-6">
            <select class="form-control" name="purpose_type" required>
                <option value="" disabled selected="selected">Please Select</option>
                <option value="increment">Increment</option>
                <option value="decrement">Decrement</option>
            </select>
        </div>
    </div>

{{--    <div class="form-group">--}}
{{--        <label class="col-sm-2 control-label       ">Is Active</label>--}}
{{--        <div class="col-sm-6">--}}
{{--            <input type="hidden" name="is_active" value="0">--}}
{{--           <input type="checkbox" name="is_active" value="1">--}}
{{--        </div>--}}
{{--    </div>--}}


    <div class="form-group" id="userTypeField">
        <label for="user_type_code" class="col-sm-2 control-label">User Type</label>
        <div class="col-sm-6">
            <select id="user_type_id" class="form-control user-type-list select2" multiple required>
                @foreach($userTypes as $userType)
                    <option value={{$userType->user_type_code}}>
                        {{$userType->user_type_name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="extra-information" style="display: none">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
               <table class="table">
                   <thead>
                   <tr>
                       <th scope="col">User Type</th>
                       <th scope="col">Is Active</th>
                       <th scope="col">Admin Control</th>
                       <th scope="col">Close For Modification</th>
                   </tr>
                   </thead>
                   <tbody id="user-types-data">
                   </tbody>
               </table>
            </div>
        </div>
    </div>

















