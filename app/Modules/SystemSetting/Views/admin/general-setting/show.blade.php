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
                                    <a href="{{ route('admin.general-settings.edit') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
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
                                  @if(isset($generalSetting))
                                        <tr>
                                        <td>Logo</td>
                                        <td>
                                            <img src="{{asset('uploads/general-setting/'.$generalSetting->logo)}}" alt="image not found" width="50" height="50">
                                        </td>
                                        </tr>
                                        <tr>
                                            <td>Favicon</td>
                                            <td>
                                                <img src="{{asset('uploads/general-setting/'.$generalSetting->favicon)}}" alt="image not found" width="50" height="50">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Admin Sidebar Logo</td>
                                            <td>
                                                <img src="{{asset('uploads/general-setting/'.$generalSetting->admin_sidebar_logo)}}" alt="image not found" width="50" height="50">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Full Address</td>
                                            <td>{{ $generalSetting->full_address }}</td>
                                        </tr>
                                        <tr>
                                            <td>Primary Contact</td>
                                            <td>{{ $generalSetting->primary_contact }}</td>
                                        </tr>
                                        <tr>
                                            <td>Secondary Contact</td>
                                            <td>{{ $generalSetting->secondary_contact }}</td>
                                        </tr>
                                        <tr>
                                            <td>Primary Bank Name</td>
                                            <td>{{ ($generalSetting->primary_bank_name)?$generalSetting->primary_bank_name:'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Primary Bank Account Number</td>
                                            <td>{{ ($generalSetting->primary_bank_account_number)?$generalSetting->primary_bank_account_number:'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Primary Bank Branch </td>
                                            <td>{{ ($generalSetting->primary_bank_branch)?$generalSetting->primary_bank_branch:'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Secondary Bank Name</td>
                                            <td>{{ ($generalSetting->secondary_bank_name)?$generalSetting->secondary_bank_name:'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Secondary Bank Account Number</td>
                                            <td>{{ ($generalSetting->secondary_bank_account_number)?$generalSetting->secondary_bank_account_number:'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Secondary Bank Branch</td>
                                            <td>{{ ($generalSetting->secondary_bank_branch)?$generalSetting->secondary_bank_branch:'N/A' }}</td>

                                        </tr>
                                        <tr>
                                            <td>Company Email Contact</td>
                                            <td>{{ $generalSetting->company_email }}</td>
                                        </tr>
                                        <tr>
                                            <td>COmpany Brief</td>
                                            <td>{{ $generalSetting->company_brief }}</td>
                                        </tr>
                                        <tr>
                                            <td>Facebook Details</td>
                                            <td>{{ $generalSetting->facebook }}</td>
                                        </tr>
                                        <tr>
                                            <td>Twitter Details</td>
                                            <td>{{ $generalSetting->twitter }}</td>
                                        </tr>
                                        <tr>
                                            <td>Instagram Details</td>
                                            <td>{{ $generalSetting->instagram }}</td>
                                        </tr>
                                        <tr>
                                            <td>Maintenance Mode</td>
                                            <td>
                                                <span class="label label-primary">
                                               {{ $generalSetting->isMaintenanceModeOn() == 1 ? 'ON' : 'OFF'}}
                                                 </span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Ip Filtering</td>
                                            <td>
                                                 <span class="label label-primary">
                                                {{$generalSetting->isIpFilteringEnabled() ? 'ON' : 'OFF'}}
                                                 </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SMS Status </td>
                                            <td>
                                                 <span class="label label-primary">
                                                {{$generalSetting->sms_enable ? 'ON' : 'OFF'}}
                                                 </span>
                                            </td>
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
