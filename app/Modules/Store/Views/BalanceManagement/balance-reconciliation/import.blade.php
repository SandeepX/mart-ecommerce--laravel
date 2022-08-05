@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
        [
            'page_title' => $title,
            'sub_title'=> "Manage {$title}",
            'icon' => 'home',
            'sub_icon' => '',
            'manage_url' => route($base_route.'.reconciliation')
        ])

        <!-- Main Content-->
        <section class="content">
            @include('Admin::layout.partials.flash_message')



            @if (Session::has('warn'))
                <div style="max-width: 100%" id="message" class="alert alert-warning {{Session::has('warning_important') ? 'alert-important': ''}}">
                    <button type="button" style="color:white !important;opacity: 1 !important;" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{session('warn')}}
                    @if(session()->has('fileName'))
                        <a href="{{url('storage/balance_reconciliation/generated/'.session()->get('fileName'))}}"  download><b>Click here to download list</b></a>
                    @endif
                </div>
            @endif



            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
{{--                            <form action="{{route('admin.balance.reconciliation.import')}}" method="POST"  enctype="multipart/form-data">--}}
{{--                                @csrf--}}
{{--                                <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row ">--}}
{{--                                        <label for="import_file">Import File</label>--}}
{{--                                        <div class="">--}}
{{--                                            <input type="file" class="form-control" name="import_file" id="import_file" required>--}}

{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                </div>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-3">--}}
{{--                                        <button type="submit" class="btn btn-block btn-primary form-control">Import</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}



{{--                            </form>--}}

                            <form action="{{route('admin.balance.reconciliation.import')}}" id="importFile" method="POST"  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label for="import" class="col-sm-2 col-form-label">Import File</label>
                                    <div class="col-sm-10">
                                        <input type="file" readonly class="form-control-plaintext" id="import" name="import_file" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-block btn-primary form-control import">Import</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <a href="{{url('samples/sample_balance_reconciliation.xlsx')}}" class="btn btn-success" download><i class="fa fa-file-excel-o"></i>  Download sample csv File</a>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



@push('scripts')

        <script>
            $('#importFile').submit(function (e, params) {
                var localParams = params || {};

                if (!localParams.send) {
                    e.preventDefault();
                }


                Swal.fire({
                    title: 'Are you sure you want to import the balance reconciliation excel file ?',
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

