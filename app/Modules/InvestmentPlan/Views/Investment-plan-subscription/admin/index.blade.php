@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            <style>
                .box-color {
                    float: left;
                    height: 15px;
                    width: 10px;
                    padding-top: 5px;
                    border: 1px solid black;
                }

                .danger-color {
                    background-color:  #ff667a ;
                }

                .warning-color {
                    background-color:  #f5c571 ;
                }

                .switch {
                    position: relative;
                    display: inline-block;
                    width: 50px;
                    height: 25px;
                }
                .switch input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                }
                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #F21805;
                    -webkit-transition: .4s;
                    transition: .4s;
                }
                .slider:before {
                    position: absolute;
                    content: "";
                    height: 17px;
                    width: 16px;
                    left: 4px;
                    bottom: 4px;
                    background-color: white;
                    -webkit-transition: .4s;
                    transition: .4s;
                }
                input:checked + .slider {
                    background-color: #50C443;
                }
                input:focus + .slider {
                    box-shadow: 0 0 1px #50C443;
                }
                input:checked + .slider:before {
                    -webkit-transform: translateX(26px);
                    -ms-transform: translateX(26px);
                    transform: translateX(26px);
                }
                /* Rounded sliders */
                .slider.round {
                    border-radius: 34px;
                }
                .slider.round:before {
                    border-radius: 50%;
                }
            </style>

            @include('Admin::layout.partials.flash_message')
            <div class="row">


                <div class="col-xs-12">

                    <div class="panel-group">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong >
                                    FILTER INVESTMENT PLAN SUBSCRIPTION
                                </strong>

                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm">
                                        <strong>Filter</strong> <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <div class="panel-body" >
                                    <div class="panel panel-default">
                                        <div class="collapse" id="filter">
                                            <div class="panel-body" >
                                                <form action="{{route('admin.investment-subscription.index')}}" method="get">

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Investment Plan Name </label>
                                                            <input type="text" class="form-control" name="investment_plan_name" id="investment_plan_name"
                                                                   value="{{$filterParameters['investment_plan_name']}}">
                                                        </div>
                                                    </div>

                                                    <button type="submit" id="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Investment Plan Subscribed
                            </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Investment Name</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody id="investment-contents">
                                @php
                                    $status = ['pending'=>'warning','accepted'=>'success','rejected'=>'danger'];
                                @endphp
                                @forelse($subscribedIP as $key => $subcriptionData)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$subcriptionData->investment_plan_name}} </td>
                                        <td>

                                            @can('View Investment Plan Subscription Details')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.investment-subscription.detail-show',$subcriptionData->investment_plan_code),'View Detail', 'eye','primary')!!}
                                            @endcan

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
                            {{$subscribedIP->appends($_GET)->links()}}
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
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function (){
            $(".fancybox").fancybox();
        });

        $
    </script>
@endpush
