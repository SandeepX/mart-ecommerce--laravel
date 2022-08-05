<div class="modal fade" id="banned-{{$user->user_code}}"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ban Modal, User:<strong> {{$user->name}} </strong>  Code: <strong>{{$user->user_code}}</strong>  Type: <strong>{{$user->userType->user_type_name}} </strong> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('admin.user-account-log.banned',$user->user_code)}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Reason</label>
                        <textarea class="form-control" name="reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
