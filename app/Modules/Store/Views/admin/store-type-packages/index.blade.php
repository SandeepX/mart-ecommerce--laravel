@extends('Admin::layout.common.masterlayout')
@push('css')
    <!-- Add fancyBox -->
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
    'page_title'=>"Store Types",
    'sub_title'=> "Manage ".$title,
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.store-types.index'),
    'extraBreadCrumbs' => [
       'Store Type Packages' => route('admin.store-type-packages.index',$storeType->store_type_code)
     ]
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message_no_validation')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Create package for : {{$storeType->store_type_name}}
                            </h3>

                        </div>


                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="createBrand"
                                          action="{{route('admin.store-type-packages.store')}}"
                                          enctype="multipart/form-data" method="post">
                                    {{csrf_field()}}

                                    @include(''.$module.'.admin.store-type-packages.common.form')

                                    <!-- /.box-body -->

                                        <div class="form-group col-md-12 text-center">
                                            <button type="submit" class="btn btn-sm btn-primary addStorePackage"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                        </div>
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
                                List of Store Type Packages Of Store Type : {{$storeType->store_type_name}}
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
                                            <th>Package Name</th>
                                            <th>Details</th>
                                            {{--                                            <th>Image</th>--}}
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">
                                        @if(isset($storeTypePackages) && $storeTypePackages->count())
                                            @foreach($storeTypePackages as $i => $storeTypePackage)
                                                <tr class="row1" data-id="{{$storeTypePackage->id}}">
                                                    <td>
                                                        <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </div>

                                                    </td>
                                                    <td>{{ucfirst($storeTypePackage->package_name)}}</td>
                                                    <td>
                                                        Refundable Registration Charge: <span
                                                            class="label label-warning">{{$storeTypePackage->refundable_registration_charge}}</span>
                                                        <br>
                                                        Non Refundable Registration Charge: <span
                                                            class="label label-primary">{{$storeTypePackage->non_refundable_registration_charge}}</span>
                                                        <br>
                                                        Base Investment: <span
                                                            class="label label-info">{{$storeTypePackage->base_investment}}</span>
                                                        <br>
                                                        Annual Purchasing Limit: <span
                                                            class="label label-success">{{$storeTypePackage->annual_purchasing_limit}}</span>
                                                        <br>
                                                        Referal Registration Incentive Amount: <span
                                                            class="label label-danger">{{$storeTypePackage->referal_registration_incentive_amount}}</span>
                                                        <br>
                                                    </td>
                                                    {{--                                                    <td><a data-fancybox="gallery" href="{{asset('uploads/stores/storetypepackages/images/'.$storeTypePackage->image)}}"><img src="{{asset('uploads/stores/storetypepackages/images/'.$storeTypePackage->image)}}" alt="{{$storeTypePackage->package_name}}" width="50px" height="50px"/>--}}
                                                    {{--                                                        </a>--}}
                                                    {{--                                                    </td>--}}
                                                    <td>
                                                        @can('Change Store Type Package Status')
                                                        @if(isset($storeTypePackage) && $storeTypePackage->count())
                                                            @if($storeTypePackage->is_active == 0)
                                                                @can('Change Store Type Package Status')
                                                                <a href="{{route('admin.store-type-packages.toggle-status',[
                                                                        'storeTPCode'=>$storeTypePackage->store_type_package_master_code,
                                                                        'status'=>'active'
                                                                    ])}}">
                                                                    @endcan
                                                                    <label class="switch">
                                                                        <input type="checkbox" value="off"
                                                                               class="change-status-store-type-package">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </a>
                                                            @elseif($storeTypePackage->is_active == 1)
                                                                    @can('Change Store Type Package Status')
                                                                <a href="{{route('admin.store-type-packages.toggle-status',[
                                                                        'storeTPCode'=>$storeTypePackage->store_type_package_master_code,
                                                                        'status'=>'inactive'
                                                                    ])}}">
                                                                    @endcan
                                                                    <label class="switch">
                                                                        <input type="checkbox" value="on"
                                                                               class="change-status-store-type-package"
                                                                               checked>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </a>
                                                            @endif
                                                        @endif
                                                        @endcan
                                                    </td>
                                                    <td>
{{--                                                        <a href="javascript:void(0);"--}}
{{--                                                           class="btn btn-xs btn-primary stpm-edit"--}}
{{--                                                           data-stpm-code="{{$storeTypePackage->store_type_package_master_code}}">--}}
{{--                                                            <i class="fa fa-edit"></i> Edit</a>--}}
                                                        @can('Update Store Type Package')
                                                          {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.store-type-packages.edit', $storeTypePackage->store_type_package_master_code),'Edit', 'pencil','primary')!!}
                                                        @endcan
                                                        {{-- {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.store-type-packages.destroy', $storeTypePackage->store_type_package_master_code),$storeTypePackage,'Delete',$storeTypePackage->package_name)!!}--}}
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
    @include(''.$module.'.admin.store-type-packages.common.edit-modal')
@endsection
@push('scripts')
    @include(''.$module.'.admin.store-type-packages.common.edit-script')
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

                    let storeTypeCode = "{{$storeType->store_type_code}}";

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
                        url: "{{ url('admin/store-type-packages/change-display-order') }}"+'/'+storeTypeCode,
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


            $('.change-status-store-type-package').on('change', function (event) {
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

