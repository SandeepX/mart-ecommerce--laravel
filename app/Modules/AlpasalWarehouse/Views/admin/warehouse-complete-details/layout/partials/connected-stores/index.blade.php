<div class="row">

    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    List of Connected Stores
                </h3>
            </div>


            <div class="box-body">

                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Store Name</th>
                        <th>Store Owner</th>
                        <th>Store Email</th>
                        <th> Store Contact Phone</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($stores as $i => $store)
                        <tr>
                            <td>{{++$i}}</td>
                            <td><a href="{{route('admin.store.complete.detail', $store->store_code)}}">{{$store->store_name}}</a></td>
                            <td>{{$store->store_owner}}</td>
                            <td>{{$store->store_email}}</td>
                            <td>{{$store->store_contact_phone}}</td>
                            <td>
                                <span class="label label-{{returnLabelColor($store->status)}}">
                                    {{$store->status}}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <p class="text-center"><b>No records found!</b></p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
                <div class="pagination" id="connected-stores-pagination">
                    @if(isset($stores))
                        {{$stores->appends($_GET)->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
