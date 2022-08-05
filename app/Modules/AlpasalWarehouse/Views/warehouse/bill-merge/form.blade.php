@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Merge Bill
                            </h3>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route('warehouse.bill-merge.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    List Of Merged Bills
                                </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <form id="filter_form" action="{{route('warehouse.bill-merge.merge')}}" method="post">
                                @csrf
                                <div class="row">
                                 <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <label for="store_code">Store</label>
                                            <select name="store_code" class="form-control select2" id="store_code">
                                                <option value="">Please Select Store</option>
                                                @foreach($stores as $store)
                                                <option value="{{$store->store_code}}">
                                                    {{$store->store_name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <label for="store_order_code">Store Orders</label>
                                            <select name="store_order_code[]" class="form-control select2" id="store_order_code" multiple>
{{--                                                <option selected value="" >--Select An Option--</option>--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <label for="store_preorder_code">Store Pre Orders</label>
                                            <select name="store_preorder_code[]" class="form-control select2" id="store_preorder_code" multiple>
                                                {{--                                                <option selected value="" >--Select An Option--</option>--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                               @can('Create Bill Merge')
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary" style="width: 155px">Merge</button>
                                        </div>
                                    </div>
                                </div>
                               @endcan
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @includeIf('AlpasalWarehouse::warehouse.bill-merge.script');
@endpush
