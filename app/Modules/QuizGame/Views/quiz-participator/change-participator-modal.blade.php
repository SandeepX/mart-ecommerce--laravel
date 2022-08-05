
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><strong>Participator Verification Form</strong></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal"
                      action="{{route('admin.quiz.participator.changeStatus',$quizParticipatorDetail->qpd_code)}}"
                      role="form" id="paticipatorVerify" method="post" >
                    @csrf
                    @method('put')
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status" class="control-label">
                                    Status</label>
                                <select id="status" name="status" class="form-control" >
                                    @if(isset($status) && count($status) > 0)
                                        <option value="">Select Status</option>
                                        @foreach($status as $value)
                                            <option value="{{$value}}"
                                                {{old('status') == $value ? 'selected' : ''}}>
                                                {{ucfirst($value)}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="remarks" class="control-label">Remarks</label>
                                <textarea id="remarks" class="form-control" name="remarks"
                                          placeholder="Enter remarks">{{old('remarks')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary" id="saveChanges">
                            Respond
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
