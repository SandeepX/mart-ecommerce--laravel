@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'subscribers.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.subscribers.index')}}" method="get">

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="subscriber">Subscriber</label>
                                        <input type="text" class="form-control" name="subscriber" id="subscriber" value="{{$filterParameters['subscriber']}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="active">Active</label>
                                        <select id="active" name="active" class="form-control">
                                            <option value="">
                                                All
                                            </option>
                                            <option value="1" {{1 == $filterParameters['active'] ?'selected' :''}}>
                                                Yes
                                            </option>
                                            <option value="0" {{0 == $filterParameters['active'] ?'selected' :''}}>
                                                No
                                            </option>

                                        </select>
                                    </div>

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
                                List of {{$title}}
                            </h3>


                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subscriber</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($subscribers as $subscriber)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$subscriber->email}}</td>
                                        <td>
                                            @if($subscriber->is_active)
                                                <span class="label label-success">On</span>
                                            @else
                                                <span class="label label-danger">Off</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{--{!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Change Status ',route($base_route.'subscribers.toggleStatus', $subscriber->subscriber_code),'Change Status', 'pencil','primary')!!}--}}

                                            @can('Delete Subscriber')
                                                {!! \App\Modules\Application\Presenters\DataTable::createDeleteAction('Delete',route($base_route.'subscribers.destroy',$subscriber->subscriber_code),$subscriber->subscriber_code,'Subscriber',$subscriber->subscriber_code)!!}
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
                                {{$subscribers->links()}}

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
