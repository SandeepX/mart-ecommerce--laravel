@extends('Admin::layout.common.masterlayout')
@push('css')
<style>
    input[type=checkbox] {
        transform: scale(1.5);
    }
    input[type=radio] {
        transform: scale(1.5);
    }
</style>
@endpush
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=> formatWords($title,true),
    'sub_title'=>'Manage '. formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit the {{$title}} : {{$lead->lead_name}}</h3>
                        </div>
                    @include('Admin::layout.partials.flash_message')
                        <div class="box-body">

                            <form method="post" action="{{route($base_route.'.update',$lead->lead_code)}}" id="form" enctype="multipart/form-data">
                                {{csrf_field()}}

                                {{ method_field('PUT') }}
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs"></ul>
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="registration">
                                            @include(''.$module.'.admin.common.form')
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection


