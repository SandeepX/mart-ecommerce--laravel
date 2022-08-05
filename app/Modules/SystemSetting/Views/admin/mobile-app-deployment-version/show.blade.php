@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "{$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.show'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">{{$title}}</h3>

                            @can('Update General Setting')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.mobile-app-deployment-version.edit') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        Edit {{$title}}
                                    </a>
                                </div>
                            @endcan

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <table class="table table-striped">
                                <tbody>
                                @if(isset($mobileAppDeploymentVersion))
                                    <tr>
                                        <td>Manager Version</td>
                                        <td>{{ $mobileAppDeploymentVersion->manager_version }}</td>
                                    </tr>

                                    <tr>
                                        <td>Manager Build Number</td>
                                        <td>{{ $mobileAppDeploymentVersion->manager_build_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Store Version</td>
                                        <td>{{ $mobileAppDeploymentVersion->store_version }}</td>
                                    </tr>
                                    <tr>
                                        <td>Store Build Number</td>
                                        <td>{{ $mobileAppDeploymentVersion->store_build_number }}</td>
                                    </tr>
                                @else
                                    <p>No Data Found</p>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection
