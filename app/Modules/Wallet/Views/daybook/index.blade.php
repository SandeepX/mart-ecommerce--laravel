@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage">

            </div>
            <br>
            <div class="row">



                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="{{route('admin.daybook.index')}}" id="daybook_filter"  method="get">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_from">Transation Date From</label>
                                        <input type="date" class="form-control" name="transaction_date_from" id="transaction_date_from"
                                               value="{{$filterParameters['transaction_date_from']}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_to">Transaction Date To</label>
                                        <input type="date" class="form-control" name="transaction_date_to" id="transaction_date_to"
                                               value="{{$filterParameters['transaction_date_to']}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="transaction_flow">Transaction Flow </label>
                                        <select name="transaction_flow" class="form-control select2" id="transaction_flow">
                                            <option value="" {{ !isset($filterParameters['transaction_flow']) && $filterParameters['transaction_flow'] == '' ? 'selected':'' }}>All</option>
                                            <option value="decrement" {{ isset($filterParameters['transaction_flow']) && $filterParameters['transaction_flow']=='decrement' ? 'selected':'' }}>Increment</option>
                                            <option value="increment" {{ isset($filterParameters['transaction_flow']) && $filterParameters['transaction_flow']=='increment' ? 'selected':'' }}>Decrement</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="transaction_type">Transaction Type : </label>
                                        <input type="radio" id="include_exclude" {{ (isset($filterParameters['include_exclude']) && $filterParameters['include_exclude'] == 'include') ? 'checked':'' }} name="include_exclude" value="include">
                                        <label for="include">Include</label>
                                        <input type="radio" id="include_exclude" {{ (isset($filterParameters['include_exclude']) && $filterParameters['include_exclude'] == 'exclude') ? 'checked':'' }} name="include_exclude" value="exclude">
                                        <label for="exclude">Exclude</label><br>
                                        <select name="transaction_type[]" class="form-control select2" multiple id="transaction_type">
{{--                                            <option value="" {{ $filterParameters['transaction_type']=='' ? 'selected':'' }}>All</option>--}}
{{--                                            @foreach($transactionPurposes as $transactionPurpose)--}}
{{--                                                <option value="{{$transactionPurpose->slug}}" {{$transactionPurpose->slug == $filterParameters['transaction_type'] ?'selected' :''}}>--}}
{{--                                                    {{$transactionPurpose->purpose}}</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                    </div>
                                </div>


                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="store">Store Name</label>
                                        <select name="store_code" class="form-control select2" id="store_code">
                                            <option value="" {{ $filterParameters['store_code']=='' ? 'selected':'' }}>All</option>
                                            @foreach($stores as $key => $value)
                                                <option value="{{$key}}" {{$key == $filterParameters['store_code'] ?'selected' :''}}>
                                                    {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-block btn-primary form-control" id="daybook-filter">Filter</button>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div style="font-size:15px" class="panel-title">
                                <span>
                                    {{ (!isset($filterParameters['transaction_date_from']) && (!isset($filterParameters['transaction_date_to'])) ? 'Daybook of Last 30 days Transactions' : 'Daybook of Transaction') }}
                                </span>
                            </div>
                        </div>

                         <div id="daybookTable">
    {{--                         main content--}}
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
    @include(''.$module.'daybook.daybook-scripts')
@endpush
