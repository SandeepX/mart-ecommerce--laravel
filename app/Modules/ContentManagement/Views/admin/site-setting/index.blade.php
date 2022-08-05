@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
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
                                    List of Site Pages
                                </h3>
                            </div>


                            <div class="box-body">
                                <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Updated At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sitePages as $i => $sitePage)
                                       <tr>
                                           <td>{{++$i}}</td>
                                           <td>{{convertToWords($sitePage->content_type)}}</td>
                                           <td>{{$sitePage->updated_at}}</td>

                                           <td>
                                               @can('Update Site Page')
                                                   {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.site-pages.edit', $sitePage->content_type),'Edit Page', 'pencil','primary')!!}
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