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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Transaction Purpose
                            </h3>
                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                @can('Create Wallet Transaction Purpose')
                                <a href="{{ route('admin.wallets.transactions-purpose.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-list"></i>
                                  Create  {{formatWords($title,true)}}
                                </a>
                                @endcan
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Purpose Code</th>
                                        <th>Purpose<br> <small>(Slug)</small></th>
                                        <th>Purpose Type</th>
                                        <th>User Type</th>
                                        <th>Admin Control</th>
                                        <th>Close for Modification</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $status = [
                                         1 => 'success',
                                         0 => 'danger'
                                  ];
                                @endphp
                                @forelse($walletTransactionsPurpose as $key =>$purpose)
                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{$purpose->wallet_transaction_purpose_code}}</td>
                                        <td>{{$purpose->purpose}}<br> <small>({{$purpose->slug}}) </small></td>
                                        <td>{{ucwords($purpose->purpose_type)}}</td>
                                        <td>{{$purpose->userType->user_type_name}}</td>
                                        <td><span class="label label-{{$status[$purpose->admin_control]}}">{{($purpose->admin_control) ? 'ON' : 'OFF'}}</span></td>
                                        <td><span class="label label-{{$status[$purpose->close_for_modification]}}">{{($purpose->close_for_modification) ? 'Yes' : 'No'}}</span></td>
                                        <td>
                                            <a href="{{route('admin.wallets.transactions-purpose.toggleStatus',$purpose->wallet_transaction_purpose_code)}}" class=" changeStatus">
                                                <span class="label label-{{$status[$purpose->is_active]}}">{{($purpose->is_active) ? 'Active' : 'Inactive'}}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                               @can('Update Wallet Transaction Purpose')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.wallets.transactions-purpose.edit', $purpose->wallet_transaction_purpose_code),'Edit Transaction Purpose', 'pencil','primary')!!}
                                            @endcan
                                            @can('Delete Wallet Transaction Purpose')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.wallets.transactions-purpose.destroy',$purpose->wallet_transaction_purpose_code),$purpose,'Transaction Purpose',$purpose->wallet_transaction_purpose_code)!!}
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
                            {{$walletTransactionsPurpose->appends($_GET)->links()}}
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
        $('.changeStatus').click(function (e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change Transaction Purpose status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    Swal.fire('changes not saved', '', 'info')
                }
            })
        })

    </script>

@endpush
