
<div class="row">

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Name  *</label>
            <input type="text" value="{{isset($warehouse->contact_name) ? $warehouse->contact_name : old('contact_name')  }}" class="form-control" name="contact_name" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Email  *</label>
            <input type="email" value="{{isset($warehouse->contact_email) ? $warehouse->contact_email : old('contact_email')  }}" class="form-control" name="contact_email" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Phone 1</label>
            <input type="number" value="{{isset($warehouse->contact_phone_1) ? $warehouse->contact_phone_1 : old('contact_phone_1')  }}" class="form-control" name="contact_phone_1" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Phone 2</label>
            <input type="number" value="{{isset($warehouse->contact_phone_2) ? $warehouse->contact_phone_2 : old('contact_phone_2')  }}" class="form-control" name="contact_phone_2" />
        </div>
    </div>

</div>


