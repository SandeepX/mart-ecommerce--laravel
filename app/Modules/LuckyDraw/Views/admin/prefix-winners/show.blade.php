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
                                List of Prefix Winner || Luckydraw Name: {{ucfirst($storeLuckydraw->luckydraw_name)}}
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
                                            <th>#</th>
                                            <th>Store Name</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">
                                        @if(isset($storeLuckydraw->orderedPrefixWinners))
                                            @foreach($storeLuckydraw->orderedPrefixWinners as $prefixWinner)
                                                <tr class="row1" data-id="{{$prefixWinner->id}}">
                                                    <td>
                                                        <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </div>

                                                    </td>
                                                    <td>{{$prefixWinner->store ? $prefixWinner->store->store_name : ''}}</td>
                                                    <td>
                                                       @if($storeLuckydraw->status === 'pending')
                                                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.prefix-winners.edit', ['PWCode'=>$prefixWinner->prefix_winner_code,
                                                         'SLCode'=>$storeLuckydraw->store_luckydraw_code]),'Edit', 'pencil','primary')!!}
                                                        {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.prefix-winners.destroy', $prefixWinner->prefix_winner_code),$prefixWinner,'Delete',$storeLuckydraw->luckydraw_name)!!}
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
    <script>
        $(document).ready(function () {
            $( "#tablecontents" ).sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    sendOrderToServer();
                }
            });

            function sendOrderToServer() {

                let luckyDrawCode = "{{$storeLuckydraw->store_luckydraw_code}}";

                var order = [];
                $('tr.row1').each(function (index, element) {
                    order.push({
                        id: $(this).attr('data-id'),
                        position: index + 1
                    });
                });

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/prefix-winners/change-display-order') }}"+'/'+luckyDrawCode,
                    data: {
                        sort_order: order,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {

                        console.log(response);
                        $('#flash_ajax_alert')
                            .addClass('alert alert-success')
                            .css('display','block')
                            .css('opacity',1)
                            .html(response.message)
                            .fadeTo(3000, 0).slideUp(1000);
                    }
                });
            }
        });
    </script>
@endpush

