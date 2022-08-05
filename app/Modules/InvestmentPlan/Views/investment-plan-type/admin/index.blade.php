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
                                    FILTER INVESTMENT PLAN Types
                                </strong>

                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm">
                                        <strong >Filter</strong> <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <div class="panel-body" >
                                    <div class="panel panel-default">
                                        <div class="expand" id="filter">
                                            <div class="panel-body">
                                                <form action="" method="get">
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Investment Type Name </label>
                                                            <input type="text" class="form-control" name="name" id="name"
                                                                   value="{{$filterParameters['name']}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="">Status</label>
                                                            <select name="is_active" class="form-control " id="is_active">
                                                                <option value="">Select All </option>
                                                                <option value="1" {{ isset($filterParameters['is_active']) && ($filterParameters['is_active'] == 1)?'selected':''}} >Active</option>
                                                                <option value="0" {{ isset($filterParameters['is_active']) && ($filterParameters['is_active'] == 0)?'selected':''}} >Inactive</option>
                                                            </select>
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
                                List of Investment Plan Types
                            </h3>


{{--                            @can('Create New Investment Plan')--}}
{{--                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">--}}
{{--                                    <a href="{{route('admin.investment-type.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">--}}
{{--                                        <i class="fa fa-plus-circle"></i>--}}
{{--                                        Add New Investment Plan Type--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            @endcan--}}


                        </div>


                        <div class="box-body">
                            <div id="investment-contents-message"></div>
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
{{--                                    <th>Description</th>--}}
                                    <th>Is_active</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody id="investment-contents">
                                @forelse($allInvestmentPlanTypes as $key => $investmentType)
                                    <tr class="row1" data-id="{{$investmentType->id}}">
                                        <td>
                                            <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                                                <i class="fa fa-ellipsis-v"></i>
                                                <i class="fa fa-ellipsis-v"></i>
                                            </div>

                                        </td>
                                        <td>{{ucfirst($investmentType->name)}} </td>
{{--                                        <td>{{ucfirst($investmentType->description)}} </td>--}}

                                        <td>
                                            <label class="switch">
                                                <input class="toggleStatus" href="{{route('admin.investment-type.toggle-status',$investmentType->ip_type_code)}}" data-InvestmentCode="{{$investmentType->ip_type_code}}" type="checkbox" {{($investmentType->is_active) === 1 ?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>

                                        <td>
                                            @can('Show Investment Plan')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.investment-type.show',$investmentType->ip_type_code ),'Detail Investment Type', 'eye','primary')!!}
                                            @endcan


{{--                                        @can('Update Investment Type')--}}
{{--                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.investment-type.edit',$investmentType->ip_type_code),'Edit Investment Type', 'pencil','warning')!!}--}}
{{--                                            @endcan--}}
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
                            {{$allInvestmentPlanTypes->appends($_GET)->links()}}

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
            $( "#investment-contents" ).sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    sendOrderToServer();
                }
            });

            function sendOrderToServer() {

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
                    url: "{{ url('admin/investment-plans/change-display-order') }}",
                    data: {
                        sort_order: order,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {

                        console.log(response);
                        $('#investment-contents-message')
                            .addClass('alert alert-success')
                            .css('display','block')
                            .css('opacity',1)
                            .html(response.message)
                            .fadeTo(3000, 0).slideUp(1000);
                    }
                });
            }
        });
        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change investment type status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }else if (result.isDenied) {
                    if (status === 0) {
                        $(this).prop('checked', true);
                    } else if (status === 1) {
                        $(this).prop('checked', false);
                    }
                }
            })
        })
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
@endpush

