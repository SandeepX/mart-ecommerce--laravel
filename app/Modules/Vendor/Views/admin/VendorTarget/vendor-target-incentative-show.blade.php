@extends('Admin::layout.common.masterlayout')
@section('content')

    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                               Show Vendor Incentative Details
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>VTI Code</th>
                                    <th>Product Name</th>
                                    <th>Product variant Name</th>
                                    <th>Starting Range</th>
                                    <th>End Range</th>
                                    <th>Incentative Type</th>
                                    <th>Has Meet Target</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($vendorTargetIncentative as $key => $value)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$value->vendor_target_incentive_code}}</td>
                                        <td>{{ucfirst($value->product_name) }}</td>
                                        <td>{{$value->product_variant_name}}</td>
                                        <td>{{getNumberFormattedAmount($value->starting_range) }}</td>
                                        <td>{{getNumberFormattedAmount($value->end_range)}}</td>
                                        <td>
                                            <span class="label label-primary">{{$value->incentive_type}}</span></td>
                                        <td>
                                            @if(isset($value->has_meet_target) && ($value->has_meet_target)==1)
                                                <span class="label label-success">Yes</span>
                                            @else
                                                <span class="label label-danger ">No</span>
                                            @endif
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
                            {{$vendorTargetIncentative->links()}}
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




@endpush

