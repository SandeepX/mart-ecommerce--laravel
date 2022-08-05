@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add A {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.visit-claim-scan-redirection.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="createBank" action="{{route($base_route.'.store')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{old('title')}}" placeholder="Enter Title" name="title" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Image</label>
                                        <div class="col-sm-6">
                                            <input type="file" value="" name="image" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">App Page</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="app_page">
                                                <option value="">Please Select</option>
                                                @foreach($appPages as $appPage)
                                                  <option value="{{$appPage}}" {{$appPage == old('app_page') ? 'selected' : ''}}>{{$appPage}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">External Link</label>
                                        <div class="col-sm-6">
                                            <input type="url" class="form-control"  value="{{old('external_link')}}" placeholder="https://" name="external_link" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label  class="col-sm-2 control-label">Is Active</label>
                                        <div class="col-sm-6">
                                            <input type="hidden" value="0" name="is_active" />
                                           <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"  />
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary addNewBankDetail">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>

    {{--    <script>--}}
    {{--            alert('its changed');--}}
    {{--    </script>--}}



@endsection


@push('scripts')
    <script>
        $('#createBank').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to create New Visit Claim scan Redirection ?',
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
