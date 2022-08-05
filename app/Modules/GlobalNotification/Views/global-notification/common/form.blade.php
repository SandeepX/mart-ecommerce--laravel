<div class="form-group">
    <label class="col-sm-2 control-label">Message</label>
    <div class="col-sm-6">

        <textarea id="message" class="form-control summernote" name="message" required autocomplete="off" placeholder="Enter message" value="{{isset($notificationDetail) ? $notificationDetail->message : ''  }}"  >{{isset($notificationDetail->message)? $notificationDetail->message: ''}}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">link</label>
    <div class="col-sm-6">
        <input type="url" class="form-control" value="{{isset($notificationDetail) ? $notificationDetail->link : old('message')  }}" placeholder="Enter the link" name="link" php k autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Created For</label>
    <div class="col-sm-6">
        @if(isset($notificationDetail))
        <select class="form-control select2"  name="created_for" required autocomplete="off">
            <option {{($notificationDetail->created_for =='all')? 'selected':''}} value ="all">All</option>
            <option {{($notificationDetail->created_for =='vendor')? 'selected':''}} value ="vendor">Vendor</option>
            <option {{($notificationDetail->created_for =='warehouse')? 'selected':''}} value="warehouse">warehouse</option>
            <option {{($notificationDetail->created_for =='store')? 'selected':''}} value="store">Store</option>
        </select>
        @else
            <select class="form-control select2"   name="created_for" required autocomplete="off">
                <option value ="all">All</option>
                <option  value ="vendor">Vendor</option>
                <option  value="warehouse">warehouse</option>
                <option  value="store">Store</option>
            </select>
        @endif
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">File</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="file" {{ !isset($notificationDetail) ? 'required' : ''  }} >
        @if(isset($notificationDetail['file']) && !empty(($notificationDetail['file'])))
        <img src="{{asset('uploads/globalNotification/files/'.$notificationDetail['file'])}}"
             alt="" width="150"
             height="150">
        @endif
    </div>


</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Start date</label>
    <div class="col-sm-6">
        <input type="date" class="form-control" value="{{isset($notificationDetail->start_date)?$notificationDetail->start_date:''}}" name="start_date" {{ !isset($notificationDetail) ? 'required' : ''  }} >
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">End date</label>
    <div class="col-sm-6">
        <input type="date" class="form-control" value="{{isset($notificationDetail->end_date)? $notificationDetail->end_date:''}}" name="end_date">
    </div>
</div>

<div class="form-group ">
    <label  class="col-sm-2 control-label">is_active</label>
    <div class="col-sm-6">
        @if(isset($notificationDetail))
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active" {{($notificationDetail->is_active==1)?'checked':''}} />
        @else
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"  />
        @endif
    </div>
</div>










