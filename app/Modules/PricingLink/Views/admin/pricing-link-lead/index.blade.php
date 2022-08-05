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
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form id="filter-form" action="{{route('admin.pricing-link-lead.index')}}" method="get">
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="province" class="control-label">Province  *</label>
                                        <select class="form-control" id="province" name="province" >
                                            <option selected value="" >--Select An Option--</option>
                                            @if(isset($provinces) && count($provinces)>0)
                                                @foreach ($provinces as $province)
                                                    <option value={{ $province->location_code }} {{ $filterParameters['province'] == $province->location_code ? 'selected': '' }}>{{ $province->location_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="district" class="control-label">District  *</label>
                                        <select name="district" class="form-control" id="district" onchange="districtChange()">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="municipality" class="control-label">Municipality  *</label>
                                        <select name="municipality" class="form-control" id="municipality" onchange="municipalityChange()">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="ward" class="control-label">Ward  *</label>
                                        <select class="form-control" id="ward"  name="ward">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label for="is_verified">Is Verified</label>
                                    <select id="is_verified" name="is_verified" class="form-control">
                                        <option value="">  All </option>
                                            <option value="1"
                                                {{(isset($filterParameters['is_verified']) && $filterParameters['is_verified'] == 1) ?'selected' :''}}>
                                                Yes
                                            </option>
                                        <option value="0"
                                            {{(isset($filterParameters['is_verified']) && $filterParameters['is_verified'] == 0) ?'selected' :''}}>
                                            No
                                        </option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <label for="joined_date_from">Joined Date From</label>
                                    <input type="date" class="form-control" name="joined_date_from" id="joined_date_from" value="{{$filterParameters['joined_date_from']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="joined_date_to">Joined Date To</label>
                                    <input type="date" class="form-control" name="joined_date_to" id="joined_date_to" value="{{$filterParameters['joined_date_to']}}">
                                </div>

                                <br>

                                <button type="submit" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px;" id="export-excell-btn">Excell Export</button>
                                <button type="submit" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px;" id="filter-btn">Filter</button>
                                <button type="button" onclick="clearForm()" class="btn btn-primary btn-sm pull-right" id="reset-btn">Reset Form</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Pricing Link Lead
                            </h3>
                        </div>
                        <div class="box-body">
                            <div id="investment-contents-message"></div>
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Mobile Number</th>
                                    <th>Location</th>
                                    <th>Pricing Master Code</th>
                                    <th>Is Verified</th>
                                    <th>Created At</th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pricingLinkLeads as $key => $pricingLinkLead)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$pricingLinkLead->full_name}} </td>
                                        <td>{{$pricingLinkLead->mobile_number}} </td>
                                        <td>{{$pricingLinkLead->getFullLocationPath()}} </td>
                                        <td>{{$pricingLinkLead->pricing_master_code}} </td>
                                        <td>
                                            @if($pricingLinkLead->is_verified == 1)
                                                <span style='font-size:20px;'>&#10004;</span>
                                            @elseif($pricingLinkLead->is_verified == 0)
                                                <span style='font-size:20px;'>&#10006;</span>
                                            @endif
                                        </td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($pricingLinkLead->created_at),'Y-M-d')}} </td>
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
                            {{$pricingLinkLeads->appends($_GET)->links()}}

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
    @includeIf('PricingLink::front.location-script')
    <script>
            $('#export-excell-btn').click(function(e){
                e.preventDefault();
                $('#filter-form').attr('action', '{{route('admin.pricing-link-lead.exportExcellPricingLinkLead')}}');
                $('#filter-form').submit();
            });
            $('#filter-btn').click(function(e){
                e.preventDefault();
                $('#filter-form').attr('action', '{{route('admin.pricing-link-lead.index')}}');
                $('#filter-form').submit();
            });

            function clearForm()
            {
                document.getElementById("filter-form").reset();
                document.getElementById("province").reset();
                document.getElementById("joined_date_from").reset();
                document.getElementById("joined_date_to").reset();
                document.getElementById("is_verified").reset();
            }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
@endpush
