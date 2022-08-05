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
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.store-lucky-draws.index')}}" method="get">
                                <div class="row">
                                <div class="col-xs-4">
                                    <label for="luckydraw_name">Luckydraw Name</label>
                                    <input type="text" class="form-control" name="luckydraw_name" id="luckydraw_name" value="{{$filterParameters['luckydraw_name']}}">
                                </div>
                                <div class="col-xs-4">
                                    <label for="store_luckydraw_code">Luckydraw Code</label>
                                    <input type="text" class="form-control"  name="store_luckydraw_code" id="store_luckydraw_code" value="{{$filterParameters['store_luckydraw_code']}}">
                                </div>

                                <div class="col-xs-4">
                                    <label for="type">Type</label>
                                    <select id="type" name="type" class="form-control">
                                        <option value="">
                                            All
                                        </option>
                                        <option value="cash" {{$filterParameters['type'] === 'cash' ? 'selected' : ''}}> Cash </option>
                                        <option value="goods" {{$filterParameters['type'] === 'goods' ? 'selected' : ''}}> Goods </option>

                                    </select>
                                </div>

                                <div class="col-xs-4">
                                    <label for="status">Luckydraw Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">
                                            All
                                        </option>
                                        <option value="open" {{$filterParameters['status'] === 'open' ? 'selected' : ''}}> Open </option>
                                        <option value="closed" {{$filterParameters['status'] === 'closed' ? 'selected' : ''}}> Closed </option>

                                    </select>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 29px">Filter</button>
                                </div>
                                <div class="col-xs-4 text-center">

                                </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Store Lucky Draw
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route('admin.store-lucky-draws.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New {{$title}}
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
                                            <th>LuckyDraw Name</th>
                                            <th>Type</th>
                                            <th>Eligibility Sales Amount</th>
                                            <th>Days</th>
                                            <th>Prize</th>
                                            <th>Status</th>
                                            <th>Is Active</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">
                                        @if(isset($storeLuckydraws) && $storeLuckydraws->count())
                                            @foreach($storeLuckydraws as $i => $storeLuckydraw)
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{ucfirst($storeLuckydraw->luckydraw_name)}}</td>
                                                    <td>{{$storeLuckydraw->type}}</td>
                                                    <td>{{$storeLuckydraw->eligibility_sales_amount}}</td>
                                                    <td>{{$storeLuckydraw->days}}</td>
                                                    <td>
                                                        {{$storeLuckydraw->prize}}
                                                    </td>
                                                    <td>
                                                      @if($storeLuckydraw->status === 'open')
                                                            <button class="btn btn-xs btn-success">{{ucfirst($storeLuckydraw->status)}}</button>
                                                      @else
                                                            <button class="btn btn-xs btn-danger">{{ucfirst($storeLuckydraw->status)}}</button>
                                                      @endif
                                                    </td>
                                                    <td>
                                                            @if(isset($storeLuckydraw) && $storeLuckydraw->count())
                                                                @if($storeLuckydraw->is_active == 0)
                                                                        <a href="{{route('admin.store-lucky-draws.change-active-status',[
                                                                        'SLCode'=>$storeLuckydraw->store_luckydraw_code,
                                                                        'status'=>'active'
                                                                    ])}}">
                                                                            <label class="switch">
                                                                                <input type="checkbox" value="off"
                                                                                       class="change-active-status-store-lucky-draws">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </a>
                                                                    @elseif($storeLuckydraw->is_active == 1)
                                                                            <a href="{{route('admin.store-lucky-draws.change-active-status',[
                                                                        'SLCode'=>$storeLuckydraw->store_luckydraw_code,
                                                                        'status'=>'inactive'
                                                                    ])}}">
                                                                                <label class="switch">
                                                                                    <input type="checkbox" value="on"
                                                                                           class="change-active-status-store-lucky-draws"
                                                                                           checked>
                                                                                    <span class="slider round"></span>
                                                                                </label>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                    </td>
                                                    <td>
                                                        @if($storeLuckydraw->status === 'pending' && $storeLuckydraw->is_active === 1)
                                                        <button type="button" class="btn btn-success btn-xs prefix-winner-btn" data-toggle="modal" data-target="#prefixWinnerModal" data-SLC="{{$storeLuckydraw->store_luckydraw_code}}">Prefix winner</button>
                                                        @endif
                                                        @if($storeLuckydraw->status === 'pending')
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.store-lucky-draws.edit', $storeLuckydraw->store_luckydraw_code),'Edit', 'pencil','primary')!!}
                                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.store-lucky-draws.destroy', $storeLuckydraw->store_luckydraw_code),$storeLuckydraw,$storeLuckydraw->luckydraw_name,' with its prefix winners')!!}
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Pre Load Stores ',route('admin.store-lucky-draws.pre-load-store-page',$storeLuckydraw->store_luckydraw_code),'Pre Load Stores', 'pencil','info')!!}
                                                        @endif
{{--                                                        @if($storeLuckydraw->status === 'pending' && $storeLuckydraw->is_active === 1)--}}
{{--                                                        <a href="{{route('admin.store-lucky-draws.open-luckydraw',$storeLuckydraw->store_luckydraw_code)}}" class="btn btn-xs btn-success change-lucky-draw-status">--}}
{{--                                                            Open Luckydraw--}}
{{--                                                        </a>--}}
{{--                                                        @endif--}}
{{--                                                        @if($storeLuckydraw->status === 'open' && $storeLuckydraw->is_active === 1 && $storeLuckydraw->eligibleWinners->count())--}}
{{--                                                        <a href="{{route('admin.store-lucky-draws.toggle-status',[--}}
{{--                                                                'SLCode'=>$storeLuckydraw->store_luckydraw_code,--}}
{{--                                                                'status'=>'closed'--}}
{{--                                                            ])}}" class="btn btn-xs btn-danger change-lucky-draw-status">--}}
{{--                                                            Close Luckydraw--}}
{{--                                                        </a>--}}
{{--                                                        @endif--}}
                                                        @if(isset($storeLuckydraw->prefixWinners) && $storeLuckydraw->prefixWinners->count() && $storeLuckydraw->is_active === 1)
                                                            <a href="{{route('admin.prefix-winners.show',$storeLuckydraw->store_luckydraw_code)}}" type="button" class="btn btn-success btn-xs" >View Prefix winner</a>
                                                        @endif
                                                        @if(($storeLuckydraw->status === 'closed' || $storeLuckydraw->status === 'open') && $storeLuckydraw->is_active === 1)
                                                            <a href="{{route('admin.store-luckydraw-winners.show',$storeLuckydraw->store_luckydraw_code)}}" type="button" class="btn btn-success btn-xs" >View winner</a>
                                                        @endif
{{--                                                        @if($storeLuckydraw->status === 'open' && $storeLuckydraw->storeLuckydrawWinners->count() && $storeLuckydraw->eligibleWinners->count() < 1)--}}
{{--                                                                <a href="{{route('admin.store-lucky-draws.re-select-winner',$storeLuckydraw->store_luckydraw_code)}}" class="btn btn-xs btn-success change-lucky-draw-status">--}}
{{--                                                                    ReSelect Winner--}}
{{--                                                                </a>--}}
{{--                                                        @endif--}}
                                                    </td>

                                                </tr>
                                                @include('LuckyDraw::admin.prefix-winners.prefix-winner-modal')
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$storeLuckydraws->appends($_GET)->links()}}
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
@push('scripts')
    @includeIf('LuckyDraw::admin.prefix-winners.prefix-winner-script')
    <script>
        $(document).ready(function () {
            $('.change-active-status-store-lucky-draws').on('change', function (event) {
                event.preventDefault();
                let current = $(this).val();
                Swal.fire({
                    title: 'Do you Want To Change Status?',
                    showCancelButton: true,
                    confirmButtonText: `Change`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.location.href = $(this).closest('a').attr('href');

                    } else {
                        if (current === 'on') {
                            $(this).prop('checked', true);
                        } else if (current === 'off') {
                            $(this).prop('checked', false);
                        }
                    }
                });
            });
            $('.change-lucky-draw-status').on('change', function (event) {
                event.preventDefault();
                let current = $(this).val();
                Swal.fire({
                    title: 'Do you Want To Change Status?',
                    showCancelButton: true,
                    confirmButtonText: `Change`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.location.href = $(this).closest('a').attr('href');

                    } else {
                        if (current === 'on') {
                            $(this).prop('checked', true);
                        } else if (current === 'off') {
                            $(this).prop('checked', false);
                        }
                    }
                });
            });
            $(".fancybox").fancybox();
        });

    </script>
    <!-- Add jQuery library -->
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>



@endpush

