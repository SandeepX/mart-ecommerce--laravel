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
                                List of Promotion Links
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                               <a href="{{ route('admin.promotion-links.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Add New Promotion Links
                               </a>
                            </div>
                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>File Name</th>
                                    <th>File</th>
                                    <th>Link Code</th>
                                    <th>Link Title</th>
                                    <th>Description</th>
                                    <th>Link Image</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($promotionLinks as $i => $promotionLink)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>
                                            {{$promotionLink->title}}
                                        </td>
                                        <td>
                                            {{$promotionLink->filename}}
                                        </td>
                                        <td>
                                            @if(isset($promotionLink->file))
                                                <a target="_blank" href="{{asset($promotionLink->getPromotionFileUploadPath().$promotionLink->file)}}">
                                                    {{ $promotionLink->file}}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{$promotionLink->link_code}}</td>
                                        <td>{{$promotionLink->og_title}}</td>
                                        <td>{{$promotionLink->og_description}}</td>
                                        <td>
                                            @if(isset($promotionLink->og_image))
                                                <a target="_blank" href="{{asset($promotionLink->getOGImageUploadPath().$promotionLink->og_image)}}">{{$promotionLink->og_image}}</a>
                                            @endif
                                        </td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.promotion-links.edit', $promotionLink->id),'Edit Promotion Link', 'pencil','primary')!!}
                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.promotion-links.destroy',$promotionLink->id),$promotionLink,'Prtomotion Link',$promotionLink->name)!!}
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
