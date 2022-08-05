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
            background-color: #ff667a;
        }

        .warning-color {
            background-color: #f5c571;
        }


    </style>
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
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        {{--                        <div class="panel-body">--}}
                        {{--                            <form action="{{route('admin.salesmanager.index')}}" method="get">--}}
                        {{--                                <div class="col-xs-4">--}}
                        {{--                                    <div class="form-group">--}}
                        {{--                                        <label for="user_name">User Type</label>--}}
                        {{--                                        <select name="user_type" class="form-control select2" >--}}
                        {{--                                            <option value="">All</option>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}

                        {{--                                <button type="submit" class="btn btn-primary form-control">Filter</button>--}}
                        {{--                            </form>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Referred Stores For: {{$manager->manager_name}}
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Package</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($referredStores as $i => $referredStore)

                                    <tr>
                                        <td>{{++$i}}</td>

                                        <td>
                                            <a href="{{route('admin.store.complete.detail',['storeCode'=> $referredStore->referred_store_code])}}">
                                                {{ucfirst($referredStore->referredStore->store_name)}}
                                                <small>({{$referredStore->referred_store_code}})</small>
                                            </a>
                                        </td>

                                        <td>
                                            @if($referredStore->is_active==1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ucfirst($referredStore->referredStore->storeTypePackage ? $referredStore->referredStore->storeTypePackage->package_name : '-')}}</td>
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
                            {{$referredStores->links()}}
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
        $('document').ready(function () {


        });
    </script>

@endpush
