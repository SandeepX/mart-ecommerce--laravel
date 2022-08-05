<div class="row">

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">User Name *</label>
            <input type="text" class="form-control" value="{{ old('name') }}" placeholder="Enter User's Full Name"
                name="name" required autocomplete="off">
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Login Email *</label>
            <input type="email" class="form-control" value="{{ old('login_email') }}" placeholder="Enter Login Email"
                name="login_email" required autocomplete="off">
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Login Phone *</label>
            <input type="number" class="form-control" value="{{ old('login_phone') }}" placeholder="Enter Login Phone"
                name="login_phone" required autocomplete="off">
        </div>
    </div>


  
</div>
