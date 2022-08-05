<form action="{{route($base_route.'reply')}}" id="composeMessage" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="department" value="{{$enquiryMessage->department}}">
    <input type="hidden" name="parent_id" value="{{$enquiryMessage->store_message_code}}">
    <input type="hidden" name="receiver_code" value="{{$enquiryMessage->sender_code}}">
    <div class="form-group">
        <label for="Subject">Subject</label>
        <input type="text" class="form-control" name="subject"  placeholder="Enter Subject">
    </div>
    <div class="form-group">
        <label for="message">Message</label>
        <textarea class="form-control" name="message" rows="10"></textarea>
    </div>

    <button type="submit" class="btn btn-primary composeMessage">Compose</button>
</form>
