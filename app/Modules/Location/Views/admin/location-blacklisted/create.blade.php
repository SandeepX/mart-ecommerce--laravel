@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title"> {{$title}}</h3>
                            @can('View BlackListed Location List')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route('admin.location-blacklisted.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <div class="row col-md-6" style="margin-left: 200px;">
                                <form class="form-horizontal" role="form" id="blacklistLocation" action="{{route($base_route.'.store')}}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="box-body">
                                        <div class="form-group" id="select_province">
                                            <label class="control-label"> Select Province</label>
                                            <select class="form-control select2" name="province" id="province" >

                                            </select>
                                        </div>

                                        <div class="form-group " id="select_district">
                                            <label class="control-label">Select District</label>
                                            <select class="form-control select2"  name="district" id="district" >

                                            </select>
                                        </div>

                                        <div class="form-group " id="select_municipality">
                                            <label class="control-label">Select Municipality Area</label>
                                            <select class="form-control select2" name="municipality" id="municipality" >

                                            </select>
                                        </div>

                                        <div class="form-group " id="select_ward">
                                            <label class="control-label">Select Ward </label>
                                            <select class="form-control select2" name="location_code" id="ward" required>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label"> Purpose </label>
                                            <select class="form-control select2" name="purpose" required>
                                                <option value="">select purpose</option>
                                                <option value="store-registration"> Store Registartion</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label"> Status </label>
                                            <select class="form-control select2" name="status" required>
                                                <option value="">select status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>


                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary blacklistLocation">BlackList Location</button>
                                    </div>
                                </form>
                            </div>
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

@push('scripts')

    <script>
            $(document).ready(function() {
                $('#select_province').show();
                $('#select_district').hide();
                $('#select_municipality').hide();
                $('#select_ward').hide();

                $.ajax({
                    url:"{{route('admin.vendorTarget.get-province')}}",
                    type:"get",
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    success:function(data) {
                        //console.log(data);
                        $('#province').append(data);
                    }
                })

                let province = $('#province');

                province.trigger('change');

                province.on('change',function(e){
                    e.preventDefault();
                    $('#select_district').show();
                    $('#district').empty();
                    $('#municipality').empty();
                    $('#ward').empty();
                    var provinceCode = $('#province').val();
                    $.ajax({
                        url:"{{route('admin.vendorTarget.get-district')}}",
                        type:"get",
                        data: {
                            provinceCode:provinceCode,
                            _token: '{{csrf_token()}}'
                        },
                        success:function(data) {
                            //console.log(data);
                            $('#district').append(data);
                        }
                    })
                });

                let district = $('#district');

                district.trigger('change');

                district.on('change',function(e){
                    e.preventDefault();
                    $('#select_municipality').show();
                    $('#municipality').empty();
                    $('#ward').empty();
                    var districtCode = $('#district').val();

                    $.ajax({
                        url:"{{route('admin.vendorTarget.get-muncilipality')}}",
                        type:"get",
                        data: {
                            districtCode:districtCode,
                            _token: '{{csrf_token()}}'
                        },
                        success:function(data) {
                            $('#municipality').append(data);
                        }
                    })
                });

                let municipality = $('#municipality');

                municipality.trigger('change');

                municipality.on('change',function(e){
                    e.preventDefault();
                    $('#select_ward').show();
                    $('#ward').empty();
                    var municipalityCode = $('#municipality').val();

                    $.ajax({
                        url:"{{route('admin.vendorTarget.get-ward')}}",
                        type:"get",
                        data: {
                            municipalityCode:municipalityCode,
                            _token: '{{csrf_token()}}'
                        },
                        success:function(data) {
                            $('#ward').append(data);
                        }
                    })
                });

                $('#blacklistLocation').submit(function (e, params) {
                    var localParams = params || {};

                    if (!localParams.send) {
                        e.preventDefault();
                    }
                    Swal.fire({
                        title: 'Are you sure you want to BlackList the Location?',
                        showCancelButton: true,
                        confirmButtonText: `Yes`,
                        padding:'10em',
                        width:'500px'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $(e.currentTarget).trigger(e.type, { 'send': true });
                            Swal.fire({
                                title: 'Please wait...',
                                hideClass: {
                                    popup: ''
                                }
                            })
                        }
                    })
                });
            });

        </script>



@endpush
