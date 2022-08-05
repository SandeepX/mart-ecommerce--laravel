@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.pre-orders-reporting.getPreordersReporting')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="store_code">Store Code</label>
                                    <input type="text" class="form-control" name="store_code" id="store_code" required>
                                    <div id="error-message-store-code" style="color: red">

                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label for="preorder_code">Preorder Code</label>
                                    <input type="text" class="form-control"  name="preorder_code" id="preorder_code" required>
                                    <div id="error-message-pre-order-code" style="color: red">

                                    </div>
                                </div>
                                <div class="col-xs-3" style="padding-top: 26px;">
                                <button type="submit" class="btn btn-primary btn-sm preorder-search">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="preorder-info">
                    @include('AlpasalWarehouse::admin.pre-order-reportings.preorder-info')
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('scripts')
    @includeIf('AlpasalWarehouse::admin.pre-order-reportings.script');
@endpush
