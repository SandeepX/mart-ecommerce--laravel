@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "{$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.show'),
    ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">{{$title}}</h3>

                            @can('Update Seo Setting')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.seo-settings.edit') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        Edit {{$title}}
                                    </a>
                                </div>
                            @endcan


                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <table class="table table-striped">
                                <tbody>
                                  @if(isset($seoSetting))
                                        <tr>
                                        <td>Meta Title</td>
                                        <td>{{ $seoSetting->meta_title }}</td>
                                        </tr>
                                        <tr>
                                            <td>Meta Description</td>
                                            <td>{{ $seoSetting->meta_description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Keywords</td>
                                            <td>{{ implode(',',json_decode($seoSetting->keywords)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Revisit After</td>
                                            <td>{{ $seoSetting->revisit_after }}</td>
                                        </tr>
                                        <tr>
                                            <td>Author</td>
                                            <td>{{ $seoSetting->author }}</td>
                                        </tr>
                                        <tr>
                                            <td>Sitemap Link</td>
                                            <td>{{ $seoSetting->sitemap_link }}</td>
                                        </tr>
                                    @else
                                        <p>No Data Found</p>
                                    @endif
                                </tbody>
                              </table>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection
