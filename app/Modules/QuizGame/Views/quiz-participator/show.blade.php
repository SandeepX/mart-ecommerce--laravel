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
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->

                    @include(''.$module.'quiz-participator.change-participator-modal')

                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-xs-12">
                                <div class="panel-group">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong>
                                                Detail Of Quiz Participator
                                            </strong>

                                            @if($quizParticipatorDetail->status == 'pending')
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <button style="margin-top: -5px;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">
                                                        <strong>Respond</strong>
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
                                                                    <div class="col-md-5">Participator Type:</div>
                                                                    <div class="col-md-7">{{$quizParticipatorDetail->participator_type}}({{$quizParticipatorDetail->participator_code}})</div>

                                                                    <div class="col-md-5">Name:</div>
                                                                    <div class="col-md-7">{{$quizParticipatorDetail->store_name}}</div>

                                                                    <div class="col-md-5">Pan Number:</div>
                                                                    <div class="col-md-7">{{$quizParticipatorDetail->store_pan_no}}</div>

                                                                    <div class="col-md-5">Address:</div>
                                                                    <div class="col-md-7">{{$quizParticipatorDetail->store_full_location}},Nepal</div>

                                                                    <div class="col-md-5">Recharge Phone Number:</div>
                                                                    <div class="col-md-7">{{$quizParticipatorDetail->recharge_phone_no}}</div>

                                                                    <div class="col-md-5">Status:</div>
                                                                    <div class="col-md-7"><span class="label label-info">{{ucfirst($quizParticipatorDetail['status'])}}</span> </div>

                                                                    <div class="col-md-5">Status Responded At:</div>
                                                                    <div class="col-md-7">{{!is_null($quizParticipatorDetail->status_reponded_at)?$quizParticipatorDetail->status_reponded_at:'N/A'}}</div>

                                                                    <div class="col-md-5">Remarks:</div>
                                                                    <div class="col-md-7">{{!is_null($quizParticipatorDetail->remarks)?$quizParticipatorDetail->remarks:'N/A'}}</div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


{{--                                        <div class="panel-body" style="height: 350px; overflow: scroll;">--}}
{{--                                            <div class="panel panel-default">--}}
{{--                                                <div class="collapse in" id="filter" aria-expanded="true" style="">--}}
{{--                                                    <div class="panel-body" style="padding-top: 0 !important;">--}}
{{--                                                        <div class="row" style="background-color: #DFF0D8; padding: 10px 0">--}}
{{--                                                            <div class="col-md-12"><strong>Document</strong></div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="row">--}}
{{--                                                            @forelse($managerDocs as $i=>$doc)--}}
{{--                                                                <div class="col-md-6">--}}
{{--                                                                    <div><strong>{{is_null($doc->doc_name)?'N/A':$doc->doc_name}}</strong></div>--}}
{{--                                                                    <a href="{{$doc->getDocumentImagePath()}}" target="_blank">--}}
{{--                                                                        <img style="height: 200px; width:200px;" src="{{$doc->getDocumentImagePath()}}">--}}
{{--                                                                    </a>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="col-md-12">--}}
{{--                                                                    <div><strong> Document No:{{is_null($doc->doc_number)?'N/A':$doc->doc_number}}}} </strong></div>--}}
{{--                                                                </div>--}}
{{--                                                                <hr>--}}
{{--                                                            @empty--}}
{{--                                                                <div class="col-md-12 ">--}}
{{--                                                                    <p class="text-center "><b>Data Not found!</b></p>--}}
{{--                                                                </div>--}}
{{--                                                            @endforelse--}}
{{--                                                        </div>--}}

{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}




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

