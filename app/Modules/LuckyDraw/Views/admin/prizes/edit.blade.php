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
                            <h3 class="box-title">Edit the LuckyDraw : {{$storeLuckydraw->luckydraw_name}}</h3>
                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.store-lucky-draws.index') }}"  style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{$title}}
                                </a>
                            </div>
                        </div>
                        @include('Admin::layout.partials.flash_message')
                        <div class="box-body">
                            <form method="post"
                                  action="{{route($base_route.'.update',$storeLuckydraw->store_luckydraw_code)}}"
                                  id="form" enctype="multipart/form-data">
                                {{csrf_field()}}

                                {{ method_field('PUT') }}
                                <div class="box-body">
                                    @include(''.$module.'.admin.prizes.common.form')
                                </div>
                                <div class="box-footer">
                                    <button type="submit" style="margin-left: 17%;"
                                            class="btn btn-sm btn-primary addStoreLuckyDraw">Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    @includeIf('LuckyDraw::admin.prizes.common.prize-script')
    <script>
        $('#form').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to edit Store Lucky Draw  ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding: '10em',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, {'send': true});
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });
    </script>
@endpush

