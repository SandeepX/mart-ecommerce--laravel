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


                            <strong>Investment Plan Type Name : {{ucfirst($investmentTypeDetail->name) }} </strong><br><br>
                            <strong>Created By: {{ $investmentTypeDetail->createdBy->name }} </strong><br><br>
                            <strong>Created At: {{ date_format($investmentTypeDetail->created_at,"Y/m/d") }} </strong><br><br>
                            <strong>Update By: {{ $investmentTypeDetail->updatedBy->name }} </strong><br><br>
                            <strong>Update At: {{ date_format($investmentTypeDetail->updated_at,"Y/m/d") }} </strong><br><br>

                            <strong>Is Active:
                                @if($investmentTypeDetail->is_active==1)
                                    <span class="label label-success">Yes</span>
                                @else
                                    <span class="label label-danger">No</span>
                                @endif
                            </strong><br><br>
                            <strong>Message:</strong><br>
                            {{ucfirst(strip_tags($investmentTypeDetail->description))}}<br><br>

                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection



