<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Person</label>
            <input type="text" class="form-control" value="{{isset($vendor) ? $vendor->contact_person : old('contact_person')  }}" name="contact_person" />
        </div>
    </div>

    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Landline</label>
            <input type="number" class="form-control" value="{{isset($vendor) ? $vendor->contact_landline :  old('contact_landline')  }}" name="contact_landline" />
        </div>
    </div>

    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Contact Mobile</label>
            <input type="number" class="form-control" value="{{isset($vendor) ? $vendor->contact_mobile :  old('contact_mobile')  }}" name="contact_mobile" />
        </div>
    </div>

    <div class="col-md-4 col-lg-3">
        <div class="form-group">
            <label class="control-label">Contact Email</label>
            <input type="text" class="form-control" value="{{isset($vendor) ? $vendor->contact_email :  old('contact_email')  }}" name="contact_email" />
        </div>
    </div>



    <div class="col-md-4 col-lg-3">
        <div class="form-group">
            <label class="control-label">Contact Fax</label>
            <input type="number" class="form-control" value="{{isset($vendor) ? $vendor->contact_fax:  old('contact_fax')  }}" name="contact_fax" />
        </div>
    </div>

</div>


