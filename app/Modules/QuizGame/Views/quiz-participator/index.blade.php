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

            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="{{ route('admin.quiz.participator.index') }}" method="get">

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="participator_type">Participator Type </label>
                                        <input type="text" class="form-control" name="participator_type" id="participator_type"
                                               value="{{($filterParameters['participator_type'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="store_name">Name </label>
                                        <input type="text" class="form-control" name="store_name" id="store_name"
                                               value="{{($filterParameters['store_name'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="recharge_phone_no">Phone Number </label>
                                        <input type="number" class="form-control" name="recharge_phone_no" id="recharge_phone_no"
                                               value="{{($filterParameters['recharge_phone_no'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="participation_from">Participated From </label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                               value="{{($filterParameters['participation_from'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="participation_to">Participated To </label>
                                        <input type="date" class="form-control" name="participation_to" id="end_date"
                                               value="{{($filterParameters['participation_to'])}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control select2" id="status">
                                            <option value="">All</option>
                                            @foreach($status as $key => $value)
                                                <option value="{{$value}}" {{($filterParameters['status'] == $value)?'selected':''}} >{{ucfirst($value)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                List of Quiz Participator
                            </h4>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Participator Type</th>
                                    <th>Name</th>
{{--                                    <th>Pan No</th>--}}
                                    <th>Phone Number</th>
                                    <th>status</th>
                                    <th>Participation Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                    $status = [
                                      'approved' => 'success',
                                      'rejected' => 'danger',
                                      'pending' => 'primary'
                                    ];

                                    ?>

                                @forelse($quizParticipator as $key =>$datum)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ucfirst($datum->participator_type)}}</td>
                                        <td>{{ucfirst($datum->store_name)}}</td>
{{--                                        <td>{{$datum->store_pan_no}}</td>--}}
                                        <td>{{$datum->recharge_phone_no}}</td>
                                        <td><label class="label label-{{$status[$datum->status]}}">{{ucfirst($datum->status)}}</label></td>
                                        <td>{{date_format($datum->created_at,'Y-M-d')}}</td>
                                        <td>
                                            @can('Show Quiz Participator Detail')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.quiz.participator.show',$datum->qpd_code ),'Detail Quiz Participator', 'eye','primary')!!}
                                            @endcan

                                            @can('Delete Quiz Passage')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.quiz.participator.destroy',$datum->qpd_code ),$datum,'QuizGame Participatore','QuizGame' )!!}
                                            @endcan

                                            @can('Show Participator Quiz Detail')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Quiz Detail ', route('admin.quiz.participator.quiz-detail',$datum->participator_code ),'Participator Quiz Detail ', 'eye','info')!!}
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
                            {{$quizParticipator->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection


{{--@push('scripts')--}}

{{--    <script>--}}
{{--        $('.toggleStatus').change(function (event) {--}}
{{--            event.preventDefault();--}}
{{--            var status = $(this).prop('checked') === true ? 1 : 0;--}}
{{--            var href = $(this).attr('href');--}}
{{--            Swal.fire({--}}
{{--                title: 'Are you sure you want to change  Quiz Passage Is Active status ?',--}}
{{--                showDenyButton: true,--}}
{{--                confirmButtonText: `Yes`,--}}
{{--                denyButtonText: `No`,--}}
{{--                padding:'10em',--}}
{{--                width:'500px',--}}
{{--                allowOutsideClick:false--}}

{{--            }).then((result) => {--}}
{{--                if (result.isConfirmed) {--}}
{{--                    window.location.href = href;--}}
{{--                }else if (result.isDenied) {--}}
{{--                    if (status === 0) {--}}
{{--                        $(this).prop('checked', true);--}}
{{--                    } else if (status === 1) {--}}
{{--                        $(this).prop('checked', false);--}}
{{--                    }--}}
{{--                }--}}
{{--            })--}}
{{--        })--}}

{{--    </script>--}}
{{--@endpush--}}
