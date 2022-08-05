@extends('Admin::layout.common.masterlayout')
@section('content')

<div class="content-wrapper">
    @includeIf('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=>''. formatWords($title,false).' from one warehouse to another',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.form'),
    ])
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">

                    <!-- /.box-header -->
                    @include("Admin::layout.partials.flash_message")
                    <div  id="showFlashMessage"></div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="text-left">
                                <h3 class="panel-title">
                                    <b>Admin Stock Transfer</b>
                                </h3>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form id="stock-transfer" method="POST" action="{{route('admin.warehouses.stock-transfer.save')}}">
                               @csrf
                                <div class="nav-tabs-custom">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="registration">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-4">
                                                    <label class="control-label">Source Warehouse</label>
                                                    <select class="form-control select2" name="source_warehouse" id="source_warehouse" required>
                                                        <option value="">Please Select Warehouse</option>
                                                        @foreach($warehouses as $warehouse)
                                                            <option value="{{$warehouse->warehouse_code}}">{{$warehouse->warehouse_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-lg-4">
                                                    <label class="control-label">Destination Warehouse</label>
                                                    <select class="form-control select2" name="destination_warehouse" id="destination_warehouse" required>
                                                        <option value="">Please Select Warehouse</option>
                                                        @foreach($warehouses as $warehouse)
                                                            <option value="{{$warehouse->warehouse_code}}">{{$warehouse->warehouse_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4 col-lg-4">
                                                    <button id="transfer-btn" class="btn btn-sm btn-primary" style="margin: 20px;">Transfer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
@push('scripts')
    <script>
              $('#stock-transfer').on('submit',function (e,params){
                  var localParams = params || {};
                  if (!localParams.send) {
                      e.preventDefault();
                  }
                  Swal.fire({
                      title: 'Are you sure you want to transfer all stocks ?',
                      showCancelButton: true,
                      confirmButtonText: `Yes`,
                      padding:'10em',
                      width:'500px'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          $(e.currentTarget).trigger(e.type, { 'send': true });
                          Swal.fire({
                              title: 'Please wait...It may take some time',
                              hideClass: {
                                  popup: ''
                              }
                          })
                      }
                  })
              });
    </script>
@endpush


