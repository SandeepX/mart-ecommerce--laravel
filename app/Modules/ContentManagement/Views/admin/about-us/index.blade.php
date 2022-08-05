@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.about-us.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of About Us
                            </h3>

                            @can('Create About Us')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.about-us.create') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add About Us
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CEO Image</th>
                                    <th>Company Name</th>
                                    <th>Ceo Name</th>
                                    <th>Company Description</th>

                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($aboutus as $i => $about)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td><img src="{{asset('uploads/contentManagement/ceo/'.$about->ceo_image)}}"
                                                 alt="{{$about->aboutUs_code}}" width="50" height="50"></td>
                                        <td>{{$about->company_name}}</td>
                                        <td>{{$about->ceo_name}}</td>
                                        <td>{{$about->company_descripion}}</td>
                                        <td>{{$about->is_active == 1? "Active":'Inactive'}}</td>
                                        <td>
                                            @can('Show About Us')

                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.about-us.show', $about->aboutUs_code),'Detail', 'eye','info')!!}


                                            @endcan
                                            @can('Update About Us')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.about-us.edit', $about->aboutUs_code),'Edit About Us', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete About Us')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.about-us.destroy',$about->aboutUs_code),$about,'About Us','')!!}
                                            @endcan


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

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
