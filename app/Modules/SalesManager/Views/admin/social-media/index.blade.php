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
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Social Media
                            </h3>


                            @can('Create Social Media')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.social-media.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Social Media
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Social Media Name</th>
                                    <th>Base Url</th>
                                    <th>Enabled For SMI</th>
                                    <th>Created By </th>
                                    <th>Created At </th>
                                    <th>Updated At </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($socialMediaDetail as $key =>$socialMedia)

                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{ucfirst(($socialMedia->social_media_name))}}</td>
                                        <td> {{(($socialMedia->base_url))}}</td>
                                        <td>
                                            @can('Change Social Media Status')
                                                <label class="switch">
                                                    <input class="toggleStatus" href="{{route('admin.social-media.toggle-status',$socialMedia->sm_code)}}" data-SocialMediaCode="{{$socialMedia->enabled_for_smi}}" type="checkbox" {{($socialMedia->enabled_for_smi) === 1 ?'checked':''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            @endcan
                                        </td>
                                        <td> {{ucfirst($socialMedia->createdBy->name)}}</td>
                                        <td> {{ date('d-M-Y',strtotime($socialMedia['created_at']))}}</td>
                                        <td> {{ date('d-M-Y',strtotime($socialMedia['updated_at']))}}</td>

                                        <td>


                                            @can('Update Social Media')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.social-media.edit',$socialMedia->sm_code ),'Edit Social Media', 'pencil','warning')!!}
                                            @endcan

                                            @can('Delete Social Media')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.social-media.destroy',$socialMedia->sm_code ),$socialMedia,$socialMedia->social_media_name,'SocialMedia' )!!}
                                            @endcan

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
        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to Change Enabled Status For SMI ?',
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
@endpush
