@extends('Admin::layout.common.masterlayout')

@section('content')
<div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "Create {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.create', $vendor->slug),
    ])

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <h3 class="box-title">Add  {{formatWords($title,true)}}</h3>

                    </div>

                    <!-- /.box-header -->
                    @include("Admin::layout.partials.flash_message")
                    <div class="box-body">
                        <form class="form-horizontal" role="form" id="vendorDocumentCreate" action="{{route($base_route.'.store', $vendor->slug)}}" enctype="multipart/form-data" method="post">
                            {{csrf_field()}}

                            <div style="" class="box-body">

                                    <div class="form-group">

                                        <div class="col-sm-6">
                                            <table class="table" id="dynamic_field">
                                                <tr>
                                                    <td>
                                                        <label>Document Name *</label>
                                                        <input type="text" class="form-control" name="document_names[]" required>
                                                    </td>

                                                    <td>
                                                        <label>Select File *</label>
                                                        <input type="file" class="form-control" name="document_files[]" required>
                                                    </td>
                                                </tr>
                                            </table>
                                            <button type="button" id="add_more" style="margin-left:8px" class="btn btn-success">Add More</button>
                                        </div>
                                    </div>


                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" style="width: 49%;" class="btn btn-block btn-primary saveVendorDocument">Add</button>
                            </div>
                        </form>
                    </div>

                    @if(isset($vendorDocuments) && count($vendorDocuments)>0)
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
                            @foreach($vendorDocuments as $i => $document)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$document->document_name}}</td>
                                <td align="left">
                                    <a class="popup-link" href="{{asset('uploads/vendor/documents/'.$document->document_file)}}">
                                    <img style="text-align:center" src="{{asset('uploads/vendor/documents/'.$document->document_file)}}" alt="{{$document->document_file}}" width="100" height="50"/>
                                    </a>
                                </td>
                                <td>
                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.vendors.documents.destroy', [$vendor->slug, $document->id]),$document,'Document',$document->document_name)!!}
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
@includeIf('Vendor::admin.vendor-document.script');
<script>
    $('#vendorDocumentCreate').submit(function (e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        Swal.fire({
            title: 'Are you sure you want to store Vendor Document?',
            showCancelButton: true,
            confirmButtonText: `Yes`,
            padding:'10em',
            width:'500px'
        }).then((result) => {
            if (result.isConfirmed) {

                $(e.currentTarget).trigger(e.type, { 'send': true });
                Swal.fire({
                    title: 'Please wait...',
                    hideClass: {
                        popup: ''
                    }
                })
            }
        })
    });
</script>

@endpush
