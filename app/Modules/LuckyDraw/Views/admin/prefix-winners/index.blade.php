@extends('Admin::layout.common.masterlayout')
@section('content')

    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>"Prefix Winner",
    'sub_title'=> "Manage ".$title,
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.prefix-winners.index'),
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
                                List of Prefix Winner
                            </h3>
{{--                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">--}}
{{--                                <a href="{{ route('admin.prefix-winners.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">--}}
{{--                                    <i class="fa fa-plus-circle"></i>--}}
{{--                                    Add New {{$title}}--}}
{{--                                </a>--}}
{{--                            </div>--}}
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
                                            <th>Store Name</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($prefixWinners) && $prefixWinners->count())
                                            @foreach($prefixWinners as $i => $prefixWinner)
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{ucfirst($prefixWinner->storeLuckydraw->luckydraw_name)}}</td>
                                                    <td>{{$prefixWinner->store->store_name}}</td>
                                                    <td>
                                                        @if($prefixWinner->storeLuckydraw->status === 'pending')
                                                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.prefix-winners.edit', ['PWCode'=>$prefixWinner->prefix_winner_code,
                                                         'SLCode'=>$prefixWinner->storeLuckydraw->store_luckydraw_code]),'Edit', 'pencil','primary')!!}
                                                        {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.prefix-winners.destroy', $prefixWinner->prefix_winner_code),$prefixWinner,'Delete',$prefixWinner->storeLuckydraw->luckydraw_name)!!}
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

