<div class="form-group">
    <label  class="col-sm-2 control-label">Manager App Version</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($mobileAppDeploymentVersion) ? $mobileAppDeploymentVersion->manager_version : old('manager_version')  }}" placeholder="Enter Manager App Version" name="manager_version"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Manager Build Number</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($mobileAppDeploymentVersion) ? $mobileAppDeploymentVersion->manager_build_number : old('manager_build_number')  }}" placeholder="Enter Manager Build Number" name="manager_build_number"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Store App Version</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($mobileAppDeploymentVersion) ? $mobileAppDeploymentVersion->store_version : old('store_version')  }}" placeholder="Enter Store App Version" name="store_version"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Store Build Number</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($mobileAppDeploymentVersion) ? $mobileAppDeploymentVersion->store_build_number : old('store_build_number')  }}" placeholder="Enter Store Build Number" name="store_build_number"  autocomplete="off">
    </div>
</div>



