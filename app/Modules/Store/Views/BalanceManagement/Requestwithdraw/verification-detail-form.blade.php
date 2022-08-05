@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'withdraw'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')

            <br>
            <div class="row">


                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Add Verification Detail
                            </h3>




                        </div>

                        <div class="box-body">
                            <form action="{{ route('admin.stores.balance-withdrawRequest.store-verification-detail',$withdrawRequestCode) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <table class="table table-bordered" id="dynamicTable">
                                    <tr>
                                        <th>Bank</th>
                                        <th>Payment Verification Source</th>
                                        <th>Amount</th>
                                        <th>Proof</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 250px !important;"><select class="form-control select2" name="addmore[0][payment_body_code]">
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->bank_code}}">{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><input type="text" name="addmore[0][payment_verification_source]"  class="form-control" /></td>
                                        <td><input type="text" name="addmore[0][amount]" class="form-control" /></td>
                                        <td><input type="file" name="addmore[0][proof]" class="form-control" /></td>
                                        <td style="width: 150px !important;"><select class="form-control" name="addmore[0][status]">
                                                <option value="passed">passed</option>
                                                <option value="failed">failed</option>
                                            </select></td>
                                        <td><input type="text" name="addmore[0][remarks]" class="form-control" /></td>
                                    </tr>

                                </table>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                    <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                </div>
                                </div>

                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
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
    @includeIf('Store::BalanceManagement.Requestwithdraw.verification-detail-script');
@endpush

