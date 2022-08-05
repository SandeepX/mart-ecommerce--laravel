
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h3 style="color: #605ca8; font-weight: bold;">Detailed of Enquiry Message</h3>
                                <p style="font-weight: light;" class="pull-right">Created: {{getNepTimeZoneDateTime($enquiryMessage->created_at)}}</p>


                            </div>
                            <div class="col-md-12">
                                <p>Sender:<b>{{$enquiryMessage->senderUser->name}}</b></p>
                                <p>Department: <b>{{$enquiryMessage->department}}</b></p>
                                <p>Subject: <b>{{$enquiryMessage->subject}}</b></p>
                            </div>
                        </div>

                    </div>
                </div>

                <section class="msger">
                    <header class="msger-header">
                        <div class="msger-header-title">
                            <i class="fas fa-comment-alt"></i> Enquiry Chat
                        </div>
                    </header>

                    <main class="msger-chat">
                        <div class="msg left-msg">
                            <div class="msg-img" style="background-image: url(https://image.flaticon.com/icons/svg/327/327779.svg)"></div>

                            <div class="msg-bubble">
                                <div class="msg-info">
                                    <div class="msg-info-name">{{$enquiryMessage->senderUser->name}}</div>
                                    <div class="msg-info-time">{{getNepTimeZoneDateTime($enquiryMessage->created_at)}}</div>
                                </div>

                                <div class="msg-text">
                                    {!! $enquiryMessage->message !!}
                                </div>

                            </div>
                        </div>
                        @foreach($repliedMessages as $repliedMessage)
                            <div class=" @if($repliedMessage->sender_code === $enquiryMessage->sender_code) msg left-msg @else msg right-msg @endif ">
                                <div class="msg-img" style=" @if($repliedMessage->sender_code === $enquiryMessage->sender_code)  background-image: url(https://image.flaticon.com/icons/svg/327/327779.svg);  @else background-image: url(https://image.flaticon.com/icons/svg/145/145867.svg); @endif"></div>

                                <div class="msg-bubble">
                                    <div class="msg-info">
                                        <div class="msg-info-name">{{$repliedMessage->senderUser->name}}</div>
                                        <div class="msg-info-time"> {{getNepTimeZoneDateTime($enquiryMessage->created_at)}}</div>
                                    </div>

                                    <div class="msg-text">
                                        {!! $repliedMessage->message !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </main>
                    @can('Reply Store Enquiry Message')
                        <form class="msger-inputarea" action="{{route('admin.enquiry-messages.reply')}}" method="POST">
                            @csrf
                            <input type="hidden" name="department" value="{{$enquiryMessage->department}}">
                            <input type="hidden" name="parent_id" value="{{$enquiryMessage->store_message_code}}">
                            <input type="hidden" name="receiver_code" value="{{$enquiryMessage->sender_code}}">
                            <input type="hidden" name="subject" value="{{$enquiryMessage->subject}}">
                            <input type="text" class="msger-input" name="message" placeholder="Enter your message...">
                            <button type="submit" class="msger-send-btn">Send</button>
                        </form>
                    @endcan
                </section>

                <!-- /.box -->
            </div>
            <!--/.col (left) -->

        </div>
        <!-- /.row -->
    </section>


