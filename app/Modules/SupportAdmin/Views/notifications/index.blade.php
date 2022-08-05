@extends('SupportAdmin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('SupportAdmin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>


                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Message</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($notifications as $notification)

                                    <tr>
                                        <td>
                                            <a href="{{ $notification->data['url'] }}">
                                                @if(isset($notification->data['image']))
                                                    <img src="{{ $notification->data['image'] }}" width="50px;" height="50px;">
                                                @else
                                                    <img src="{{asset('default/images/product-default.jpg')}}" width="50px;" height="50px;">
                                                @endif
                                                @if (isset($notification->read_at))
                                                    {{ $notification->data['message'] }}
                                                @else
                                                    <b> {{ $notification->data['message'] }} </b>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
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

                            {{$notifications->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
