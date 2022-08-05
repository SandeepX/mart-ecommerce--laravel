<div class="modal fade" id="replyModal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reply Message</h4>
            </div>
            <form action="{{route($base_route.'reply')}}" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                    @csrf
                    <input type="hidden" name="department" value="{{$enquiryMessage->department}}">
                    <input type="hidden" name="parent_id" value="{{$enquiryMessage->store_message_code}}">
                    <input type="hidden" name="receiver_code" value="{{$enquiryMessage->sender_code}}">
                    <input type="hidden" name="subject" value="{{$enquiryMessage->subject}}">
{{--                    <div class="form-group">--}}
{{--                        <label for="Subject">Subject</label>--}}
{{--                        <input type="text" class="form-control" name="subject"  placeholder="Enter Subject">--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control summernote" name="message" rows="10"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Compose</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
