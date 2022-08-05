@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Show Detail {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <div class="box-body">


                            <strong>Investment : {{ ucfirst($investmentDetail->name) }} </strong><br><br>
                            <strong>Investment Plan Type Name : {{ isset($investmentDetail->investmentType) ? ucfirst($investmentDetail->investmentType->name): 'N/A' }} </strong><br><br>
                            <strong>Paid Up Capital(Rs.): {{ isset($investmentDetail->paid_up_capital) ? ($investmentDetail->paid_up_capital): 'N/A' }} </strong><br><br>
                            <strong>Per Unit Share Price(Rs.) : {{ isset($investmentDetail->per_unit_share_price) ? ($investmentDetail->per_unit_share_price): 'N/A' }} </strong><br><br>
                            <strong>Maturity Period : {{ ($investmentDetail->maturity_period) }} </strong><br><br>
                            <strong>Target Capital(Rs.): {{ ($investmentDetail->target_capital) }} </strong><br><br>
                            <strong>Price Start Range : {{ ($investmentDetail->price_start_range) }} </strong><br><br>
                            <strong>Price End Range : {{ ($investmentDetail->price_end_range) }} </strong><br><br>
                            <strong>Interest Rate(%) : {{ ($investmentDetail->interest_rate) }} </strong><br><br>

                            <strong>Is Active:
                                @if($investmentDetail->is_active==1)
                                    <span class="label label-success">Yes</span>
                                @else
                                    <span class="label label-danger">No</span>
                                @endif
                            </strong><br><br>

                            <strong>Description:</strong><br>
                            {!! ucfirst($investmentDetail->description) !!}<br><br>

                            <strong>Terms And Condition:</strong><br>
                            {!! ucfirst($investmentDetail->terms) !!}<br><br>

                            <div>
                                <strong>Image:</strong><br>
                                <img src="{{asset('uploads/investment/images/'.$investmentDetail['image'])}}"
                                     alt="" width="350"
                                     height="300">
                            </div><br>
                            <strong>Created By: {{ $investmentDetail->createdBy->name }} </strong><br><br>
                            <strong>Created At: {{ date_format($investmentDetail->created_at,'d M y') }} </strong><br><br>
                            <strong>Update By: {{ $investmentDetail->updatedBy->name }} </strong><br><br>
                            <strong>Update At: {{ date_format($investmentDetail->updated_at,'d M y') }} </strong><br><br>

                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection



