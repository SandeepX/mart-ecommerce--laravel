@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                     <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of {{  formatWords($title,true)}}
                                </h3>


                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.leads.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{$title}}
                                    </a>
                                </div>



                            </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Landmark</th>
                                    <th>Created On </>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $i => $lead)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$lead->lead_name}}</td>
                                            <td>{{stringLimit($lead->lead_email,20)}}</td>
                                            <td>{{$lead->lead_phone_no}}</td>
                                            <td title="{{$lead->lead_landmark}}">{{stringLimit($lead->lead_landmark,25)}}</td>
                                            <td>{{$lead->created_at}}</td>
                                            <td>

                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.leads.edit', $lead->lead_code),'Edit lead', 'pencil','primary')!!}
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction("Documents (". ($lead->documents_count)." )",route('admin.leads.documents.create', $lead->lead_code),'Show / Add Lead Documents', 'eye','info')!!}
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.leads.destroy',$lead->lead_code),$lead,'Delete Lead ',$lead->lead_code)!!}

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