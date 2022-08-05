@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "Create {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.create', $lead->lead_code),
    ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add  {{formatWords($title,true)}} [ Lead : {{$lead->lead_name}} ]</h3>
                            <div class="pull-right" style="margin-top: 0px;margin-left: 10px;">
                                <a href="{{ route('admin.leads.index') }}" style="border-radius: 0px; "
                                   class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of Leads
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" action="{{route($base_route.'.store', $lead->lead_code)}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">
                                        <tr>
                                            <div class="form-group">
                    
                                                <div class="col-sm-12">
                                                    <table class="table" id="dynamic_field">
                                                        <tr>
                                                            <td style="width:20%">
                                                            <!--From Controller -->
                                            
                                                                {!! $documentTypeSelectHtmlPartial !!} 
                                                        
                                                            </td>

                                                            <td style="width:25%">
                                                                <input type="file" class="form-control" name="document_files[]" required >
                                                            </td>

                                                            <td style="width:60%">
                                                        
                                                                <input type="text" class="form-control" placeholder="Enter Remarks" name="remarks[]" >
                                                               
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <button type="button" id="add_more" style="display:block;margin: 0 auto;" class="btn btn-info">Add More</button>
                                                </div>
                                            </div>
                                          
                                        </tr>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width:50%;display:block;margin: 0 auto;" class="btn btn-success">Add</button>
                                </div>
                            </form>
                        </div>

                        @if(isset($leadDocuments) && count($leadDocuments)>0)

                        <table id="{{ $base_route }}-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($leadDocuments as $i => $document)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$document->document_type}}</td>
                                        <td>
                                            <a target="_blank" href="{{asset($document->leadDocumentFolder.$document->document_file)}}">{{ $document->document_file }}</a>
                                        </td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.leads.documents.destroy', [$lead->lead_code, $document->id]),$document,'Document',$document->document_type)!!}
                                        </td>

                                    </tr>
                                    @endforeach
                            </tbody>

                        </table>

                        @endif
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>
@endsection
@push('scripts')
<script>
     $(document).ready(function(){ 
      
      var i=1;  
      var documentTypeSelectPartial = `{!! $documentTypeSelectHtmlPartial !!}`;
      console.log(documentTypeSelectPartial);
      $('#add_more').click(function(){  
           i++;
           $('#dynamic_field').append('<tr id="row'+i+'"><td>'+documentTypeSelectPartial+'</td><td><input type="file" class="form-control" name="document_files[]" required></td><td><input type="text" class="form-control" placeholder="Enter Remarks" name="remarks[]" ></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
           $(this).remove();  
      });  
    
 });
</script>
@endpush
