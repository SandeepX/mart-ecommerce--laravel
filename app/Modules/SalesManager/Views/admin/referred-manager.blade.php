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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Referred Managers For: {{$manager->manager_name}}
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered " cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Is Active</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($referredManagers as $i => $referredManager)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$referredManager->referredManager->manager_name}}</td>
                                        <td>{{ucwords($referredManager->referredManager->status)}}</td>
                                        <td><span class="label label-{{($referredManager->referredManager->is_active) ? 'success' : 'danger'}}">{{($referredManager->referredManager->is_active) ? 'Yes' : 'No'}}</span></td>
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
                            {{$referredManagers->links()}}
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
