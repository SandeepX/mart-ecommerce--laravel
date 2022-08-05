@extends('Admin::layout.common.masterlayout')

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
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.vendors.index')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="vendor_name">Vendor Name</label>
                                    <input type="text" class="form-control" name="vendor_name" id="vendor_name" value="{{$filterParameters['vendor_name']}}">
                                </div>
                                <div class="col-xs-3">
                                    <label for="vendor_owner">Vendor Owner</label>
                                    <input type="text" class="form-control"  name="vendor_owner" id="vendor_owner" value="{{$filterParameters['vendor_owner']}}">
                                </div>



                                <div class="col-xs-3">
                                    <label for="company_type">Vendor Type</label>
                                    <select id="company_type" name="company_type" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($companyTypes as $companyType)
                                            <option value="{{$companyType->company_type_code}}"
                                                    {{$companyType->company_type_code == $filterParameters['company_type'] ?'selected' :''}}>
                                                {{$companyType->company_type_name}}
                                            </option>
                                        @endforeach
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


                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
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
                                List of {{  formatWords($title,true)}}
                            </h3>

                            @can('Create Vendor')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.vendors.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{$title}}
                                    </a>
                                </div>
                            @endcan

                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Logo</th>
                                    <th>Code</th>
                                    <th>Vendor Owner</th>
                                    <th title="format(province-district-municipality-ward">Vendor Location</th>
                                    <th>Vendor Type</th>
                                    <!-- <th>Company Type</th> -->
                                    {{--<th>Landmark</th>--}}
                                    <th>Joined Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($vendors as $i => $vendor)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>
                                            <a href="{{route('admin.vendor.complete.detail', $vendor->vendor_code)}}">
                                                {{$vendor->vendor_name}}
                                            </a>
                                        </td>
                                        <td>
                                            <img src="{{asset($vendor->getLogoUploadPath().$vendor->vendor_logo)}}"
                                                 alt="Vendor Logo" width="50" height="50">
                                        </td>
                                        <td>{{$vendor->vendor_code}}</td>
                                        <td>{{$vendor->vendor_owner}}</td>
                                        <td>{{$vendor->getFullLocationPath()}}</td>
                                        <td>{{$vendor->vendorType->vendor_type_name ?? ''}}</td>
                                        <!-- <td>{{$vendor->companyType->company_type_name ?? ''}}</td> -->
                                        {{--<td>{{$vendor->vendor_landmark}}</td>--}}
                                        <td>
                                            {{date('M j Y', strtotime($vendor->created_at))}}
                                        </td>
                                        <td>
                                            @can('Update Vendor Status')
                                            @if($vendor->is_active == 0)
                                                <a href="{{route('admin.vendor.toggle-status',[
                                                                        'vendorCode'=>$vendor->vendor_code,
                                                                        'status'=>'active'
                                                                    ])}}">
                                                    <label class="switch">
                                                        <input type="checkbox" value="off"
                                                               class="change-vendor-status">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </a>
                                            @elseif($vendor->is_active == 1)
                                                <a href="{{route('admin.vendor.toggle-status',[
                                                                        'vendorCode'=>$vendor->vendor_code,
                                                                        'status'=>'inactive'
                                                                    ])}}">
                                                    <label class="switch">
                                                        <input type="checkbox" value="on"
                                                               class="change-vendor-status"
                                                               checked>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </a>
                                            @endif
                                            @endcan
                                        </td>
                                        <td>

                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                        data-toggle="dropdown">
                                                    Actions
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('Show Vendor')
                                                        <li>
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.vendors.show', $vendor->vendor_code),'Detail', 'eye','info')!!}

                                                        </li>
                                                    @endcan

                                                    @can('Create Vendor Document')
                                                        <li>
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Documents ',route('admin.vendors.documents.create', $vendor->slug),'Documents', 'eye','info')!!}

                                                        </li>
                                                    @endcan

                                                    @can('Update Vendor')
                                                        <li>
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.vendors.edit', $vendor->vendor_code),'Edit Vendor', 'pencil','primary')!!}
                                                        </li>

                                                    @endcan

                                                    @can('Update Vendor Admin')

                                                        <li>
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Admin Password ',route('admin.vendor-password.edit', $vendor->vendor_code),'Update Vendor Admin Password', 'pencil','primary')!!}
                                                        </li>
                                                    @endcan

                                                <!-- {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Banners ',route('admin.vendors.banners.create', $vendor->slug),'Banners', 'eye','info')!!} -->

{{--                                                    @can('Delete Vendor')--}}
{{--                                                        <li>--}}
{{--                                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.vendors.destroy',$vendor->vendor_code),$vendor,'Location',$vendor->vendor_code)!!}--}}
{{--                                                        </li>--}}

{{--                                                    @endcan--}}
                                                </ul>
                                            </div>

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
                            {{$vendors->appends($_GET)->links()}}
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
@includeIf('Vendor::admin.scripts.vendor-filter-script');
<<<<<<< HEAD
    <script>
        $(document).ready(function(){
            $('.change-vendor-status').on('change', function (event) {
                event.preventDefault();
                let current = $(this).val();
                Swal.fire({
                    title: 'Do you Want To Change Status?',
                    showCancelButton: true,
                    confirmButtonText: `Change`,
                    allowOutsideClick: false
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
        })
    </script>
=======
>>>>>>> role_permission_31aug
@endpush
