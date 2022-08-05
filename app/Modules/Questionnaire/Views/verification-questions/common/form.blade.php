    <div class="form-group">
        <label class="col-sm-2 control-label">Question</label>
        <div class="col-sm-6">
            <textarea id="question" class="form-control" name="question" required autocomplete="off" placeholder="Enter message">{{isset($actionVerificationQuestion->question) ? $actionVerificationQuestion->question :old('question')}}</textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Action</label>
        <div class="col-sm-6">
           <select class="form-control select2" name="action" required>
               <option value="">All</option>
               @foreach($actions as $action)
                    <option
                        value="{{$action}}"
                            @if(isset($actionVerificationQuestion->action) && $actionVerificationQuestion->action == $action)
                                selected
                            @elseif($action==old('action')) selected
                            @endif>
                   {{ucwords(str_replace('_',' ',$action))}}</option>
               @endforeach
           </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Entity</label>
        <div class="col-sm-6">
            <select class="form-control select2" name="entity" required>
                <option value="">All</option>
                @foreach($entities as $entity)
                    <option value="{{$entity}}"
                            @if(isset($actionVerificationQuestion->entity) && $actionVerificationQuestion->entity == $entity)
                                selected
                            @elseif($action==old('entity')) selected
                            @endif>{{ucwords(str_replace('_',' ',$entity))}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group ">
        <label  class="col-sm-2 control-label">Is Active</label>
        <div class="col-sm-6">
           <input type="hidden" value="0" name="is_active"/>
           <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"
             @if(isset($actionVerificationQuestion->is_active) && $actionVerificationQuestion->is_active) checked  @elseif(old('is_active')) checked @endif
           />
        </div>
    </div>










