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
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->

                    @include(''.$module.'admin.manager-smi.manager-smi-modal')

                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-xs-12">
                                <div class="panel-group">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong>
                                                Detail For Social Media Influencer
                                            </strong>

                                            @if($managerSMIDetail->status == 'pending')
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <button style="margin-top: -5px;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">
                                                        <strong>Respond</strong>
                                                    </button>
                                                </div>
                                            @endif

                                            @if($managerSMIDetail->status != 'pending' && $managerSMIDetail->allow_edit == 0)
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <button style="margin-top: -5px;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editAllowModal">
                                                        <strong>Edit Allow</strong>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="panel-body" style="height: 350px;">
                                            <div class="panel panel-default">
                                                <div class="collapse in" id="filter" aria-expanded="true" style="">
                                                    <div class="panel-body" style="padding-top: 0 !important;">
                                                        <div class="row" style="background-color: #DFF0D8; padding: 10px 0">
                                                            <div class="col-md-12"><strong>Detail</strong></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="row" style="font-size: 18px;">
                                                                    <div class="col-md-5">Name:</div>
                                                                    <div class="col-md-7">{{$managerSMIDetail->manager->manager_name}}</div>

                                                                    <div class="col-md-5">Email:</div>
                                                                    <div class="col-md-7">{{$managerSMIDetail->manager->manager_email}}</div>

                                                                    <div class="col-md-5">Phone Number:</div>
                                                                    <div class="col-md-7">{{$managerSMIDetail->manager->manager_phone_no}}</div>

                                                                    <div class="col-md-5">Temporary Address:</div>
                                                                    <div class="col-md-7">{{$managerSMIDetail->manager->temporary_full_location}}, Nepal</div>

                                                                    <div class="col-md-5">Permanent Address:</div>
                                                                    <div class="col-md-7">{{$managerSMIDetail->manager->permanent_full_location}}, Nepal</div>

                                                                    <div class="col-md-5">Status:</div>
                                                                    <div class="col-md-7"><span class="label label-info">{{ucfirst($managerSMIDetail['status'])}}</span> </div>

                                                                    <div class="col-md-5">Is Active:</div>
                                                                    <div class="col-md-7"><span class="badge badge-primaary">{{$managerSMIDetail['is_active']?'Active':'Inactive'}}</span> </div>

                                                                    @if(!is_null($managerSMIDetail->allow_edit_remarks) && $managerSMIDetail->allow_edit == 1 )
                                                                        <div class="col-md-5">Allow Edit Remark:</div>
                                                                        <div class="col-md-7">
                                                                                <div class="btn-group pull-left" role="group" aria-label="...">
                                                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#allowRemarkModal">
                                                                                      <i class="fa fa-eye"></i>  view
                                                                                    </button>
                                                                                </div>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <img style="height: 170px; width:170px;" src="{{asset('uploads/user/avatar/'.$managerSMIDetail->manager->manager_photo)}}" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="panel-body" style="height: 350px; overflow: scroll;">
                                            <div class="panel panel-default">
                                                <div class="collapse in" id="filter" aria-expanded="true" style="">
                                                    <div class="panel-body" style="padding-top: 0 !important;">
                                                        <div class="row" style="background-color: #DFF0D8; padding: 10px 0">
                                                            <div class="col-md-12"><strong>Document</strong></div>
                                                        </div>

                                                        <div class="row">
                                                            @forelse($managerDocs as $i=>$doc)
                                                                <div class="col-md-6">
                                                                    <div><strong>{{is_null($doc->doc_name)?'N/A':$doc->doc_name}}</strong></div>
                                                                    <a href="{{$doc->getDocumentImagePath()}}" target="_blank">
                                                                        <img style="height: 200px; width:200px;" src="{{$doc->getDocumentImagePath()}}">
                                                                    </a>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div><strong> Document No:{{is_null($doc->doc_number)?'N/A':$doc->doc_number}}}} </strong></div>
                                                                </div>
                                                                <hr>
                                                             @empty
                                                                <div class="col-md-12 ">
                                                                    <p class="text-center "><b>Data Not found!</b></p>
                                                                </div>
                                                            @endforelse
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="panel-body" style="height: 280px; overflow: auto;">
                                            <div class="panel panel-default">
                                                <div class="collapse in" id="filter" aria-expanded="true" style="">
                                                    <div class="panel-body" style="padding-top: 0 !important;">
                                                        <div class="row" style="background-color: #DFF0D8; padding: 10px 0">
                                                            <div class="col-md-12"><strong>Social Media Link</strong></div>
                                                        </div>

                                                        @forelse($managerSMIDetail['managerLinks'] as $key => $value)
                                                            <div class="row">
                                                                <div class="col-md-12">

                                                                    <h4><strong>{{ucfirst($value->socialMedia->social_media_name)}}</strong></h4>

                                                                    @forelse(json_decode($value->social_media_links) as $link)
                                                                        <div>{{$link}}</div>
                                                                    @empty
                                                                        <p>Link Not Found</p>
                                                                    @endforelse

                                                                </div>
                                                            </div>
                                                        @empty
                                                            <p>Manager Social Media Link Detail Not Found</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('scripts')
    <script>
        $('document').ready(function(){

            $('#status').change(function(e){
                e.preventDefault();
                var status = $(this).val();
                if(status==='rejected'){
                    $('#remarks').prop('required',true);
                }else{
                    $('#remarks').prop('required',false);
                }
            })
        });
    </script>
@endpush

