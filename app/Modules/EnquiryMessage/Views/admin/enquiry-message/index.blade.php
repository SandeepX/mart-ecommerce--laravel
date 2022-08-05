@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Inbox Messages
                            </h3>
                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Department</th>
                                    <th>Sender</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($enquiryMessages as $enquiryMessage)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$enquiryMessage->subject}}</td>
                                        <td>{{$enquiryMessage->department}}</td>
                                        <td>{{$enquiryMessage->senderUser->name}}</td>
                                        <td>{{date('d-m-Y',strtotime($enquiryMessage->created_at))}}</td>

                                        <td>

                                            @can('Show Store Enquiry Message')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route($base_route.'show', $enquiryMessage->id),'View Detail', 'pencil','primary')!!}
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
