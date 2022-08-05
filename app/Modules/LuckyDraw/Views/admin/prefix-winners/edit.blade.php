@extends('Admin::layout.common.masterlayout')
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
                            <h3 class="box-title">Edit the Prefix Winner</h3>
                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.prefix-winners.show',$storeLuckydrawCode) }}"  style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of {{$title}}
                                </a>
                            </div>
                        </div>
                        @include('Admin::layout.partials.flash_message')
                        <div class="box-body">
                            <form method="post"
                                  action="{{route($base_route.'.update',$prefixWinner->prefix_winner_code)}}"
                                  id="form" enctype="multipart/form-data">
                                {{csrf_field()}}

                                {{ method_field('PUT') }}
                                <div class="box-body">
                                    <div class="form-group col-md-8">
                                        <label class="control-label  @error('store_code') text-red @enderror">

                                          Eligible  Store

                                            <span class="text-red">*</span></label>
                                        <div>
                                            <select class="form-control mx-1 select2" name="store_code">
                                              <option value="">Select Store</option>
                                                @foreach($eligibleStores as $store)
                                                 <option value="{{$store->store_code}}" {{$store->store_code === $prefixWinner->store_code? 'selected' : ''}}>{{$store->store_name .'('.$store->store_code.')'}}</option>
                                                @endforeach
                                            </select>
                                            @error('store_code')
                                            <small class="text-red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-8">
                                        <label class="control-label  @error('store_code') text-red @enderror">

                                           Not Eligible  Store

                                            <span class="text-red">*</span></label>
                                        <div>
                                            <select class="form-control mx-1 select2" name="store_code">
                                                <option value="">Select Store</option>
                                                @foreach($notEligibleStores as $store)
                                                    <option value="{{$store->store_code}}" {{$store->store_code === $prefixWinner->store_code? 'selected' : ''}}>{{$store->store_name .'('.$store->store_code.')'}}</option>
                                                @endforeach
                                            </select>
                                            @error('store_code')
                                            <small class="text-red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label class="control-label">Remarks </label>
                                        <div>
                                            <textarea class="form-control summernote col-md-12" name="remarks">{{isset($prefixWinner) ? $prefixWinner->remarks : old('remarks')  }}</textarea>
                                            @error('remarks')
                                            <small class="text-red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="store_luckydraw_code" value="{{$prefixWinner->store_luckydraw_code}}" />
                                </div>
                                <div class="box-footer">
                                    <button type="submit" style="margin-left: 17%;"
                                            class="btn btn-sm btn-primary">Save
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
    <script>
        $('#form').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to edit Prefix Winner  ?',
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

