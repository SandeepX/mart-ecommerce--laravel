
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Prefix Winner Of Store Lucky Draw : {{$storeLuckydrawDetail->luckydraw_name}}
    </h4>
</div>
<form action="{{route('admin.prefix-winners.store')}}" method="POST" id="submit_form">

<div class="modal-body">
    @csrf
        <div class="form-group">
            <label for="store" class="col-form-label">Eligible Store:</label>
            <select class="form-control select2" id="eligible" name="store_code[]" multiple>
              <option value="" disabled>Select Store</option>
                @foreach($eligibleStores as $store)
                    <option value="{{$store->store_code}}">{{$store->store_name .'('.$store->store_code.')'}}</option>
                @endforeach
            </select>
        </div>
    <div class="form-group">
        <label for="store" class="col-form-label">Not Eligible Store:</label>
        <select class="form-control select2" id="notEligible" name="store_code[]" multiple>
            <option value="" disabled>Select Store</option>
            @foreach($notEligibleStores as $store)
                <option value="{{$store->store_code}}">{{$store->store_name .'('.$store->store_code.')'}}</option>
            @endforeach
        </select>
    </div>
        <div class="form-group">
            <label for="remarks" class="col-form-label">Remarks:</label>
            <textarea class="form-control" name="remarks" id="remarks"></textarea>
        </div>
     <input type="hidden" name="store_luckydraw_code" value="{{$storeLuckydrawDetail->store_luckydraw_code}}" />
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary storePrefixWinner">Save</button>
</div>
</form>
<script type="text/javascript">
   $(document).ready(function(e){

       // $('.select2').select2();
       $('#eligible').select2();
       $('#notEligible').select2();


   })
</script>

