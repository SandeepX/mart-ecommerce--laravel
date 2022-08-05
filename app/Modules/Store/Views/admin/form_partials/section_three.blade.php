<div class="row">

    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Landline</label>
            <input type="number" class="form-control" value="{{isset($store) ? $store->store_contact_phone :  old('store_contact_phone')  }}" name="store_contact_phone" />
        </div>
    </div>

    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Mobile</label>
            <input type="number" class="form-control" value="{{isset($store) ? $store->store_contact_mobile :  old('store_contact_mobile')  }}" name="store_contact_mobile" />
        </div>
    </div>

    <div class="col-md-4 col-lg-3">
        <div class="form-group">
            <label class="control-label">Contact Email</label>
            <input type="text" class="form-control" value="{{isset($store) ? $store->store_email :  old('store_email')  }}" name="store_email" />
        </div>
    </div>
    
</div>


