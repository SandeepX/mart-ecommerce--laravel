@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>formatWords($title,true),
        'sub_title'=> "Show the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box">
                        <div class="box-header with-border">

                            <h3 class="box-title">Details of {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{formatWords($title,true)}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <h4>Job Title : {{$jobApplication->jobOpening->title}}</h4>
                                        <h4>Tracking Code : {{$jobApplication->tracking_code}}</h4>
                                        <h4>Applicant Name : {{$jobApplication->name}}</h4>
                                        <h4>Email : {{$jobApplication->email}}</h4>
                                        <h4>Gender : {{$jobApplication->gender}}</h4>
                                        <h4>Phone no. : {{$jobApplication->phone_num}}</h4>
                                        <h4>Optional Contacts :
                                            @if(isset($otherContacts) && is_array($otherContacts))
                                                @foreach($otherContacts as $otherContact)
                                                    <ul>
                                                       <li> {{$otherContact}}</li>
                                                    </ul>
                                                @endforeach
                                            @endif

                                        </h4>
                                        <h4>Temporary Location: {{$jobApplication->tempLocation->location_name}}</h4>
                                        <h4>Temporary Local Address: {{$jobApplication->temp_local_address}}</h4>
                                        <h4>Permanent Location: {{$jobApplication->permanentLocation->location_name}}</h4>
                                        <h4>Permanent Local Address: {{$jobApplication->perm_local_address}}</h4>
                                        <h4>
                                            Documents:
                                            @foreach($jobApplicationDocuments as $applicationDocument)
                                                <ul>
                                                    <h5>
                                                        {{$applicationDocument->document_type}} :
                                                        <a href="{{asset(\App\Modules\Career\Models\JobApplication::IMAGE_PATH.$applicationDocument->document)}}" download>
                                                            {{$applicationDocument->document}}
                                                        </a>
                                                    </h5>
                                                </ul>

                                            @endforeach
                                        </h4>

                                        <h4>
                                            Job Question Answers
                                            @forelse($jobApplicationAnswers as $jobAnswer)
                                                <ul>
                                                   <li>{{$jobAnswer->jobQuestion->question}}</li>
                                                    <p>
                                                        {{$jobAnswer->answer}}
                                                    </p>
                                                </ul>

                                            @empty
                                                <ul>
                                                    <p>N/A</p>
                                                </ul>

                                            @endforelse
                                        </h4>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
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

