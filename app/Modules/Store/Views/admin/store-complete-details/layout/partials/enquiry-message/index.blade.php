<section class="content-header">
    <h1>
        Enquiry Message
        <small>Manage Store Enquiry Message</small>
    </h1>
{{--    <ol class="breadcrumb">--}}
{{--        <li><a href="javascript:void(0)"></i> Dashboard</a></li>--}}
{{--        <li class="active"><a href="javascript:void(0)"><i class="fa fa-"></i> Contact Message</a></li>--}}
{{--    </ol>--}}
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        List of Enquiry Message
                    </h3>

                </div>

                <div class="box-body">
                    <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Store Message Code</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Sender</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($enquiryMessages as $key =>$value)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{$value->store_message_code }}</td>
                            <td>{{$value->department }}</td>
                            <td>{{$value->subject }}</td>
                            <td>{{$value->message }}</td>
                            <td>{{$value->senderUser->name}}</td>
                            <td>{{date('d-m-Y',strtotime($value->created_at))}}</td>

                            @can('Show Store Enquiry Message')
                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View',route('admin.enquiry-messages.show', $value->id),'View Detail', 'pencil','primary')!!}
                            @endcan
                        </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <p class="text-center"><b>No Messages found!</b></p>
                                </td>

                            </tr>
                        @endforelse

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
