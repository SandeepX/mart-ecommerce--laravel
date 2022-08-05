@extends('Admin::layout.common.masterlayout')
@push('css')
    <!-- Add fancyBox -->`
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>
@endpush
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
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
            height: 18px;
            width: 18px;
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
            -webkit-transform: translateX(35px);
            -ms-transform: translateX(35px);
            transform: translateX(35px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 25px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>"Store Lucky Draw",
    'sub_title'=> "Manage ".$title,
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.store-lucky-draws.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Stores {{isset($cachedStores) ? '|| Total Stores: '. $totalStores :'' }}
                               || Store Luckydraw Name: {{$storeLuckydraw->luckydraw_name}} ({{$storeLuckydraw->store_luckydraw_code}})
                                {{isset($lastPreLoadedTime) ? '|| LastPreLoadedTime: '.$lastPreLoadedTime : ''}}
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route('admin.store-lucky-draws.pre-load-store-lists',$storeLuckydraw->store_luckydraw_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Start Loading Stores
                                </a>
                            </div>
                        </div>


                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="flash_ajax_alert"></div>
                                    <table class="table table-bordered table-striped" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Store Name</th>
                                            <th>Location</th>
                                            <th>Purchase Meet</th>
                                            <th>Eligibility</th>
                                            <th>Is Approved</th>
                                            <th>Is Active</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">
                                        @if(isset($cachedStores) && $cachedStores->count())
                                            @foreach($cachedStores as $i => $store)
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{ucfirst($store->store_name)}} ({{$store->store_code}})</td>
                                                    <td>{{$store->store_full_location}}</td>
                                                    <td>
                                                        @if($store->purchase_eligibility === 1)
                                                            <button class="btn btn-xs btn-success">Yes</button>
                                                        @else
                                                            <button class="btn btn-xs btn-danger">No</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($store->eligibility === 1)
                                                            <button class="btn btn-xs btn-success">Yes</button>
                                                        @else
                                                            <button class="btn btn-xs btn-danger">No</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($store->is_approved === 1)
                                                            <button class="btn btn-xs btn-success">Yes</button>
                                                        @else
                                                            <button class="btn btn-xs btn-danger">No</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($store->is_active === 1)
                                                            <button class="btn btn-xs btn-success">Yes</button>
                                                        @else
                                                            <button class="btn btn-xs btn-danger">No</button>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$cachedStores->appends($_GET)->links()}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

