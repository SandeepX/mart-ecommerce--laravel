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
                                List of Quiz Passage
                            </h3>


                            @can('Create New Passage')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{route('admin.quiz.passage.create')}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Quiz Passage
                                    </a>
                                </div>
                            @endcan

                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Quiz Title</th>
{{--                                    <th>Passage</th>--}}
                                    <th>Quiz Date</th>
                                    <th>Is Active</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                     @forelse($quizPassage as $key =>$datum)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ucfirst($datum->passage_title)}}</td>
{{--                                            <td> {{substr(ucfirst((strip_tags($datum->passage))),0,20)}}</td>--}}
                                            <td>
                                                @foreach($datum->quizDates as $key => $value)
                                                    <li>{{$value->quiz_passage_date}}</li>
                                                @endforeach
                                            </td>

                                            <td>

                                                @can('Change Quiz Passage Status')
                                                    <label class="switch">
                                                        <input class="toggleStatus" href="{{route('admin.quiz.passage.toggle-status',$datum->qp_code)}}" data-InvestmentCode="{{$datum->qp_code}}" type="checkbox" {{($datum->passage_is_active) === 1 ?'checked':''}}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                @endcan
                                            </td>

                                            <td>
                                                @can('Show Quiz Passage Detail')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.quiz.passage.show',$datum->qp_code ),'Detail Quiz Passage', 'eye','primary')!!}
                                                @endcan

                                                @can('Update Quiz Passage')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ', route('admin.quiz.passage.edit',$datum->qp_code ),'Edit Quiz Passage', 'pencil','warning')!!}
                                                @endcan

                                                @can('Delete Quiz Passage')
                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete ',route('admin.quiz.passage.destroy',$datum->qp_code ),$datum,'QuizGame Passage','QuizGame' )!!}
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
                            {{$quizPassage->appends($_GET)->links()}}
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
                title: 'Are you sure you want to change  Quiz Passage Is Active status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px',
                allowOutsideClick:false

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
