@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #ff667a ;
        }

        .warning-color {
            background-color:  #f5c571 ;
        }


    </style>
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">

                    <div class="panel-group">
                        <div class="panel panel-success">

                            <div class="panel-heading">
                                <strong >
                                    Filter {{formatWords($title,true)}}
                                </strong>
                            </div>

                            <div>
                                <div class="panel-body">
                                    <form action="{{route('admin.manager-pay-per-visits.index')}}" method="get">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Manager Name</label>
                                                            <input type="text" name="manager_name" id="manager_name" class="form-control" value="{{$filterParameters['manager_name']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="amount_condition">Amount Condition</label>
                                                            <select name="amount_condition" class="form-control select2" id="amount_condition">
                                                                <option value="" {{$filterParameters['amount_condition'] == ''}}>All</option>
                                                                @foreach($priceConditions as $key=>$priceCondition)
                                                                    <option value="{{$priceCondition}}"
                                                                        {{$priceCondition == $filterParameters['amount_condition'] ?'selected' :''}}>
                                                                        {{ucwords($key)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_name">Amount</label>
                                                            <input type="text" name="amount" id="amount" class="form-control" value="{{$filterParameters['amount']}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route('admin.manager-pay-per-visits.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{formatWords($title,true)}}
                                </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Manager</th>
                                    <th>Amount</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($managerPayPerVisits as $i => $payPerVisit)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$payPerVisit->manager->manager_name}} ( {{$payPerVisit->manager_code}} )</td>
                                        <td>{{getNumberFormattedAmount($payPerVisit->amount)}}</td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($payPerVisit->updated_at))}}</td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit',route('admin.manager-pay-per-visits.edit', $payPerVisit->manager_pay_per_visit_code),'edit Pay Per Visit Detail', 'pencil','primary')!!}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <p class="text-center"><b>No records found!</b></p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{$managerPayPerVisits->appends($_GET)->links()}}
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

@endpush
