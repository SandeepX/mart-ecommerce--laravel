@extends('SupportAdmin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            @include("SupportAdmin::layout.partials.flash_message")
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-5">
                                <h3 style="margin-left:10px; font-weight: bold;">Support Admin For Store </h3>
                            </div>

                            <div class="col-md-4">
{{--                                <a style="margin-top: 15px !important; margin-left: 500px; " class="btn btn-danger" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseExample">--}}
{{--                                    <i class="fa  fa-filter"></i>--}}
{{--                                </a>--}}


                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default " id="collapseFilter" style="background-color: #E4E4E4">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{route('support-admin.search-store')}}" method="post">
                                        @csrf
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <label for="store_code">Store Code</label>
                                                <input type="text" class="form-control" name="store_code" id="store_code" autocomplete="off"

                                                       value="" >
                                            </div>
                                        </div>



                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <label for="store_name">Store Name</label>
{{--                                                <select class="form-control " id="store_slug " name="store_slug">--}}
{{--                                                    <option value=""></option>--}}
{{--                                                    @foreach($store as $key => $value)--}}
{{--                                                        <option value="{{$value->slug}}">{{($value['store_name'])}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
                                                <input type="text" class="form-control" name="store_name" id="store_name" autocomplete="off"

                                                       value="">
                                            </div>
                                        </div>

                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <label for="store_email">Store Email</label>
                                                <input type="email" class="form-control" name="store_email" id="store_email" autocomplete="off"

                                                       value="">
                                            </div>
                                        </div>

                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <label for="store_phone">Store Contact Mobile</label>
                                                <input type="number" class="form-control" name="store_phone" id="store_phone" autocomplete="off"

                                                       value="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary" >Find Store</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>
@endsection






