@extends('Admin::layout.common.masterlayout')
@push('css')
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
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
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])


    <!-- Main content -->
        <section class="content">
{{--            @include('Admin::layout.partials.flash_message')--}}
            @include('Admin::layout.partials.flash_message_no_validation')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>


                            @can('Create Store Type')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route("{$base_route}.create") }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{$title}}
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">
                            <div id="flash_ajax_alert_for_store_types"></div>
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="store-type-content">
                                @foreach($storeTypes as $i => $storeType)
                                    <tr class="row1" data-id="{{$storeType->id}}" data-STC="{{$storeType->store_type_code}}">
                                        <td>
                                            <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                                                <i class="fa fa-ellipsis-v"></i>
                                                <i class="fa fa-ellipsis-v"></i>
                                            </div>

                                        </td>
                                        <td>{{$storeType->store_type_name}}</td>
                                        <td><a data-fancybox="gallery" href="{{asset('uploads/storetypes/images/'.$storeType->image)}}"><img src="{{asset('uploads/storetypes/images/'.$storeType->image)}}" alt="{{$storeType->store_type_name}}" width="50px" height="50px"/>
                                            </a>
                                        </td>
                                        <td>{{$storeType->store_type_code}}</td>
                                        <td>
                                            @can('Change Store Type Status')
                                            @if(isset($storeType) && $storeType->count())
                                                @if($storeType->is_active == 0)
                                                    <a href="{{route('admin.store-types.toggle-status',[
                                                                        'storeTypeCode'=>$storeType->store_type_code,
                                                                        'status'=>'active'
                                                                    ])}}" >
                                                        <label class="switch">
                                                            <input type="checkbox" value="off" class="change-status-store-type">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </a>
                                                @elseif($storeType->is_active == 1)
                                                    <a href="{{route('admin.store-types.toggle-status',[
                                                                        'storeTypeCode'=>$storeType->store_type_code,
                                                                        'status'=>'inactive'
                                                                    ])}}" >
                                                        <label class="switch">
                                                            <input type="checkbox" value="on" class="change-status-store-type" checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </a>
                                                @endif
                                            @endif
                                            @endcan
                                        </td>
{{--                                        <td>{{$storeSize->remarks}}</td>--}}
                                        <td>
                                            @can('Update Store Type')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route($base_route.'.edit', $storeType->store_type_code),"Edit {$title}", 'pencil','primary')!!}
                                            @endcan

{{--                                            @can('Change Store Type Status')--}}
{{--                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route($base_route.'.toggle-status', $storeType->store_type_code),'Change Status', 'pencil','primary')!!}--}}
{{--                                            @endcan--}}
                                                @can('View Store Type Package Lists')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Packages',route('admin.store-type-packages.index',$storeType->store_type_code),"Packages", 'gift','primary')!!}
                                                @endcan
{{--                                                @can('Delete Store Type')--}}
{{--                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route($base_route.'.destroy',$storeType->store_type_code),$storeType,"Delete {$title}",$storeType->store_type_name)!!}--}}
{{--                                                @endcan--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
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

            $( "#store-type-content" ).sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    sendOrderToServer();
                }
            });

            function sendOrderToServer() {

                let storeTypeCode = $('tr.row1').attr('data-STC');
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
                    url: "{{ url('admin/store-types/change-display-order') }}"+'/'+storeTypeCode,
                    data: {
                        sort_order: order,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                       console.log(order,'sort order')
                        $('#flash_ajax_alert_for_store_types')
                            .addClass('alert alert-success')
                            .css('display','block')
                            .css('opacity',1)
                            .html(response.message)
                            .fadeTo(3000, 0).slideUp(1000);
                    }
                });
            }

            $('.change-status-store-type').on('change',function (event){
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
@endpush
