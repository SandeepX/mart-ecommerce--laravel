@extends('Admin::layout.common.masterlayout')
@section('content')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #ff667a ;
        }

        .warning-color {
            background-color:  #f5c571 ;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 20px;
        }


    </style>
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Manage ".formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">

                    <div class="panel-group">
                        <div class="panel panel-success">

                            <div class="panel-heading">
                                <strong >
                                    FILTER SALES MANAGER STORES
                                </strong>
                            </div>

                            <div>
                                <div class="panel-body">
                                    <form action="{{route('admin.salesManager.mangerStoreLocation')}}" method="get">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="col-xs-12">
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="user_name">Sales Manager</label>
                                                            <select name="manager_code" class="form-control select2" >
                                                                <option value="">Select Sales Manager</option>
                                                                @foreach($salesManager as $manager)
                                                                    <option value="{{$manager['manager_code']}}" {{$filterParam['manager_code'] == $manager['manager_code'] ? 'selected' : ''}}>{{ucwords($manager['manager_name'])}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="user_name">Filter By</label>
                                                            <select name="filter_type" class="form-control select2" >

                                                                <option value="">All</option>
                                                                <option value="referrals" {{$filterParam['filter_type'] == 'referrals' ? 'selected' : ''}}>Referrals</option>
                                                                <option value="diary" {{$filterParam['filter_type'] == 'diary' ? 'selected' : ''}}>Diary</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Maps of {{formatWords($title,true)}}
                            </h3>
                        </div>

                        <div class="box-body">
                            @if(count($storeLocations))
                                <div id="mapCanvas" style="height:500px"></div>
                            @else
                                <div style="height:500px;text-align:center;padding-top:15%">
                                    <p>store not found &nbsp;:(</p>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection
@push('scripts')
    <script>
        function initialize() {
            const locations = {!! json_encode($storeLocations) !!};

            var mapProp= {
                center:new google.maps.LatLng(27.6933451,84.2680326),
                zoom:7,
            };
            var map = new google.maps.Map(document.getElementById("mapCanvas"),mapProp);



            for(i=0 ; i<locations.length;i++){
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i].latitude,locations[i].longitude),
                    map: map,
                    title: locations[i].store_name,
                });
                const contentString = '<strong>' + locations[i].store_name + '</strong><br/>' + locations[i].location;
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {

                        const infoWindow = new google.maps.InfoWindow({
                            content: contentString,
                            disableAutoPan: true,
                        });

                        infoWindow.open(map, marker);
                    }
                })(marker, i));
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkShwqbN4_vK84kDHYqGU1PC4Cm9M-zgM&&callback=initialize"
            async defer></script>

@endpush
