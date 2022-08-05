@extends('Admin::layout.common.masterlayout')
@push('css')
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>
@endpush
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>"Store Lucky Draw Winner",
    'sub_title'=> "Manage ".$title,
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.store-luckydraw-winners.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">

                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Store Lucky Draw Winner || Luckydraw Name: {{ucfirst($storeLuckydraw->luckydraw_name)}}
                                <span>({{$storeLuckydraw->store_luckydraw_code}})</span>
                            </h3>
                        </div>


                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="flash_ajax_alert"></div>
                                    <table class="table table-bordered table-striped" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Store Name</th>
                                            <th>Remarks</th>
                                            <th>Eligibility Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">
                                        @if(isset($storeLuckydraw->storeLuckydrawWinners) && $storeLuckydraw->count())
                                            @foreach($storeLuckydraw->storeLuckydrawWinners as $storeLuckydrawWinner)
                                                <tr>
                                                    <td>{{$storeLuckydrawWinner->store ? $storeLuckydrawWinner->store->store_name : ''}}</td>
                                                    <td>{{$storeLuckydrawWinner->remarks}}</td>
                                                    <td>
                                                        @if($storeLuckydrawWinner->winner_eligibility === 1)
                                                            <button class="btn btn-xs btn-success"><span style='font-size:15px;'>&#10004;</span></button>
                                                        @else
                                                            <button class="btn btn-xs btn-danger"><span style='font-size:15px;'>&#10006;</span></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>

                                    </table>
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

            $(".fancybox").fancybox();
        });
    </script>
    <!-- Add jQuery library -->
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>



@endpush

