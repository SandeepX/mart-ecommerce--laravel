@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #ff667a ;
        }

        .warning-color {
            background-color:  #f5c571 ;
        }


    </style>
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),


    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route($base_route.'index')}}" method="get">
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="career">Career</label>
                                        <select name="careerCode" class="form-control select2" >
                                            <option value="">All</option>
                                            @foreach($career as $career)
                                                <option value="{{$career->career_code}}" {{ $filterParameters['careerCode'] == $career->career_code ? "selected" : '' }}>{{$career->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{$filterParameters['name']}}">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="email">From</label>
                                        <input type="date" class="form-control" name="appliedFrom" id="appliedfrom" value="{{$filterParameters['appliedFrom']}}">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label for="joined_date_from">To</label>
                                    <input type="date" class="form-control" name="appliedTo" id="appliedTo" value="{{$filterParameters['appliedTo']}}">
                                </div>
                                <button type="submit" class="btn btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>

{{--                            @can('Create Admin')--}}
{{--                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">--}}
{{--                                    <a href="{{ route("{$base_route}.create") }}" style="border-radius: 0px; "--}}
{{--                                       class="btn btn-sm btn-info">--}}
{{--                                        <i class="fa fa-plus-circle"></i>--}}
{{--                                        Add New {{$title}}--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            @endcan--}}
                        </div>


                        <div class="box-body">

                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Career</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Created At</th>


                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($candidates as $i => $candidate)
{{--                                    <tr class=" @if($user->isBanned())bg-danger @elseif($user->isSuspended())bg-warning @endif">--}}
                                        <td>{{++$i}}</td>
                                        <td>{{$candidate->name}}</td>
                                        <td>{{$candidate->careers->title}}</td>
                                        <td>{{$candidate->email}}</td>
                                        <td>{{$candidate->phone_number}}</td>
                                        <td>{{$candidate->gender}}</td>
                                        <td>{{getReadableDate(getNepTimeZoneDateTime($candidate->created_at))}}</td>
                                        <td>

                                                @can('Update Candidate')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Show ',route('admin.candidates.show', $candidate->candidate_code),'Show Candidate', 'eye','primary')!!}
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

{{--                            {{$users->links()}}--}}

                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
