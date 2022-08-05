@extends('Admin::layout.common.masterlayout')
@push('css')
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
@endpush

@section('content')
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
                        <form action="{{route('admin.stores.index')}}" method="get">

                            <div class="col-xs-3">
                                <label for="store_name">Store Name</label>
                                <input type="text" class="form-control" name="store_name" id="store_name" value="{{$filterParameters['store_name']}}">
                            </div>
                            <div class="col-xs-3">
                                <label for="store_owner">Store Owner</label>
                                <input type="text" class="form-control"  name="store_owner" id="store_owner" value="{{$filterParameters['store_owner']}}">
                            </div>

                            <div class="col-xs-3">
                                <label for="registration_type">Registration Type</label>
                                <select id="registration_type" name="registration_type" class="form-control">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach($registrationTypes as $registrationType)
                                        <option value="{{$registrationType->registration_type_code}}"
                                                {{$registrationType->registration_type_code == $filterParameters['registration_type'] ?'selected' :''}}>
                                            {{$registrationType->registration_type_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-xs-3">
                                <label for="company_type">Company Type</label>
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
                                <label for="joined_date_from">PAN / VAT No.</label>
                                <input type="text" class="form-control" name="pan_vat_no" id="pan_vat_no" value="{{$filterParameters['store_pan_vat_no']}}">
                            </div>

                            <div class="col-xs-3">
                                <label for="joined_date_from">Contact No.</label>
                                <input type="text" class="form-control" name="store_contact_no" id="store_contact_no" value="{{$filterParameters['store_contact_no']}}">
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
                            <div class="col-xs-3">
                                <label for="store_status">Store Status</label>
                                <select id="store_status" name="store_status" class="form-control">
                                    <option value="">
                                        All
                                    </option>
                                    <option value="pending"> Pending </option>
                                    <option value="processing"> Processing </option>
                                    <option value="approved"> Approved </option>
                                    <option value="rejected"> Rejected </option>

                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm pull-right">Filter</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            List of {{ formatWords($title,true)}}
                        </h3>

                        @can('Create Store')
                        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                            <a href="{{ route('admin.stores.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                <i class="fa fa-plus-circle"></i>
                                Add New {{$title}}
                            </a>
                        </div>
                        @endcan


                    </div>


                    <div class="box-body">
                        <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Logo</th>
                                    <th>Store Owner</th>
                                    <th> Balance</th>
                                    <th title="format(province-district-municipality-ward">Store Location</th>
{{--                                    <th>Registration Type</th>--}}
{{--                                    <th>Company Type</th>--}}
                                    <th>Status</th>
                                    <th>Joined Date</th>
                                    <th>Purchase Power</th>
                                    <th>Active/Inactive</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stores as $i => $store)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td><a href="{{route('admin.store.complete.detail', $store->store_code)}}">{{$store->store_name}}</a></td>
                                    <td>
                                        <img src="{{asset($store->getLogoUploadPath().$store->store_logo)}}" alt="Store Logo" width="50" height="50">
                                    </td>
                                    <td>{{$store->store_owner}}</td>
                                    <td>
                                        <span class="label label-primary">{{$store->current_balance}}</span>
                                    </td>
                                    <td>{{$store->getFullLocationPath()}}</td>
                                    <td>{{ucfirst($store->status)}}</td>

                                    <td>
                                        {{date('M j Y', strtotime($store->created_at))}}
                                    </td>
                                    <td>
                                        @can('Update Store Purchase Power')
                                            <a href="{{route('admin.store.purchase-power.toggle-status',$store->store_code)}}">
                                                <label class="switch">
                                                    <input type="checkbox" value="on" class="toggle-purchase-power-status" {{($store->has_purchase_power)?'checked':''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('Update Store Status')
                                        @if($store->is_active == 0)
                                            <a href="{{route('admin.store.toggle-status',[
                                                                        'storeCode'=>$store->store_code,
                                                                        'status'=>'active'
                                                                    ])}}">
                                                <label class="switch">
                                                    <input type="checkbox" value="off"
                                                           class="change-store-status">
                                                    <span class="slider round"></span>
                                                </label>
                                            </a>
                                        @elseif($store->is_active == 1)
                                            <a href="{{route('admin.store.toggle-status',[
                                                                        'storeCode'=>$store->store_code,
                                                                        'status'=>'inactive'
                                                                    ])}}">
                                                <label class="switch">
                                                    <input type="checkbox" value="on"
                                                           class="change-store-status"
                                                           checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </a>
                                        @endif
                                        @endcan
                                    </td>
                                    <td>


                                    <div class="dropdown">
                                        <button class="btn bt btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            Actions
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </button>
                                        <ul style="margin-left:-100px" class="dropdown-menu">

                                            @can('Impersonate')
                                                <li>
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Impersonate ',route('admin.impersonate',$store->store_code),'Impersonate', 'user','primary','_blank')!!}
                                                </li>
                                            @endcan

                                            @can('Show Store')
                                            <li>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Show ',route('admin.stores.show', $store->store_code),'Show store', 'eye','info')!!}
                                            </li>
                                            @endcan

                                            @can('Update Store')
                                            <li>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.stores.edit', $store->store_code),'Edit store', 'pencil','primary')!!}
                                            </li>
                                            <li>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Admin Password ',route('admin.store-password.edit', $store->store_code),'Update Store Admin Password', 'pencil','primary')!!}
                                            </li>

                                            @endcan

                                            {{-- {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Banners ',route('admin.stores.banners.create', $store->slug),'Banners', 'eye','info')!!} --}}
                                            @can('Create Store Document')
                                            <li>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Documents ',route('admin.stores.documents.create', $store->slug),'Documents', 'eye','info')!!}
                                            </li>

                                            @endcan

                                            @can('Show Store Warehouse')
                                                <li>
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View Warehouses ',route('admin.stores.warehouses.show', $store->store_code),'Show', 'eye','info')!!}
                                                </li>

                                            @endcan
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

                        {{$stores->appends($_GET)->links()}}
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
        $('.toggle-purchase-power-status').on('change',function (event){
            event.preventDefault();
            let current = $(this).val();
            Swal.fire({
                title: 'Do you Want To Change Purchase Power Status?',
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
        $('.change-store-status').on('change', function (event) {
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
    </script>
@includeIf('Store::admin.scripts.store-filter-script');
@endpush


