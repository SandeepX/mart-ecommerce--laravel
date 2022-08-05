{{--                 change status/respond Modal--}}
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- modal header  -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Verification Status</h4>
            </div>
            <div class="modal-body">
                <!-- begin modal body content  -->
                <form id="verificationForm" action="{{route('admin.manager-smi.changeStatus',$managerSMIDetail->msmi_code)}}" method="post">
                    @method('put')
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label>Status:</label>
                            <select class="form-control input-sm " name="status" id="status">
                                @foreach($status as $key => $value)
                                    <option value="{{$value}}" {{($managerSMIDetail['status'] == $value)? 'selected':''}} >{{ucfirst($value)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Remarks:</label>
                            <textarea placeholder="Remarks" id="remarks" name="remarks" class="form-control">{{$managerSMIDetail->remarks}}</textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <!-- modal footer  -->
                        <button type="submit" class="btn btn-primary submit" style="margin:10px;">Save</button>
                    </div>
                </form>
                <!-- end modal body content  -->
            </div>
        </div>

    </div>
</div>
{{--                    end here--}}

{{--                    Allow Edit Modal--}}
<div class="modal fade" id="editAllowModal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- modal header  -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Allow Edit Permission</h4>
            </div>
            <div class="modal-body">
                <!-- begin modal body content  -->
                <form id="" action="{{route('admin.manager-smi.toggle-allow-edit-status',$managerSMIDetail->msmi_code)}}" method="post">
                    @method('put')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label>Permission Status:</label>
                            <select required class="form-control" readonly="true" name="allow_edit" id="allow_edit">
                                <option value="1" selected >Edit Allowed</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Edit Allow Remark:</label>
                            <textarea required placeholder="Edit Allow Remarks" id="allow_edit_remarks" name="allow_edit_remarks" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <!-- modal footer  -->
                        <button type="submit" class="btn btn-primary submit" style="margin:10px;">save</button>
                    </div>
                </form>
                <!-- end modal body content  -->
            </div>
        </div>

    </div>
</div>
{{--                    end of Edit Allow Modal--}}

{{--                    Allow Edit Remark--}}
<div class="modal fade" id="allowRemarkModal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- modal header  -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><b>Allow Edit Remark</b></h4>
            </div>
            <div class="modal-body">
                <!-- begin modal body content  -->
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control">{{ucfirst($managerSMIDetail->allow_edit_remarks)}}</textarea>
                        </div>
                    </div>
                </form>
                <!-- end modal body content  -->
            </div>
        </div>

    </div>
</div>
{{--                    End Here--}}
